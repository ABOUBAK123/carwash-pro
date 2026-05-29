<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\SmsNotification;
use App\Models\LoyaltyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $invoices = Invoice::where('carwash_id', $carwashId)
            ->with('employee', 'service')
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::where('carwash_id', $carwashId)->where('is_active', true)->get();
        $services = Service::where('carwash_id', $carwashId)->where('is_active', true)->get();

        return view('invoices.index', compact('invoices', 'employees', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'nullable|string|max:200',
            'client_phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|string|max:100',
            'vehicle_plate' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $carwashId = Auth::user()->carwash_id;
        $service = Service::findOrFail($data['service_id']);
        $employee = Employee::findOrFail($data['employee_id']);

        $commissionRate = $employee->commission_rate / 100;
        $commission = $service->price * $commissionRate;

        DB::transaction(function () use ($data, $carwashId, $service, $employee, $commission) {
            $invoice = Invoice::create([
                'carwash_id' => $carwashId,
                'client_name' => $data['client_name'] ?? 'Client',
                'client_phone' => $data['client_phone'] ?? null,
                'vehicle_brand' => $data['vehicle_brand'],
                'vehicle_plate' => $data['vehicle_plate'],
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'employee_id' => $employee->id,
                'employee_commission' => $commission,
                'total_amount' => $service->price,
                'status' => 'paid',
                'invoice_number' => Invoice::generateNumber($carwashId),
            ]);

            // Créer un rendez-vous associé
            Appointment::create([
                'carwash_id' => $carwashId,
                'client_name' => $data['client_name'] ?? 'Client',
                'client_phone' => $data['client_phone'] ?? null,
                'vehicle_brand' => $data['vehicle_brand'],
                'vehicle_plate' => $data['vehicle_plate'],
                'service_name' => $service->name,
                'service_id' => $service->id,
                'employee_id' => $employee->id,
                'appointment_date' => now()->toDateString(),
                'appointment_time' => now()->toTimeString(),
                'status' => 'in_progress',
            ]);

            // Mettre à jour les stats de l'employé
            $employee->increment('total_cars_washed');
            $employee->increment('total_earnings', $commission);

            // SMS de début de service
            if (!empty($data['client_phone'])) {
                SmsNotification::create([
                    'phone_number' => $data['client_phone'],
                    'message' => "Le lavage de votre {$data['vehicle_brand']} ({$data['vehicle_plate']}) a commencé. Service: {$service->name}.",
                    'type' => 'service_start',
                    'status' => 'pending',
                ]);
            }

            // Enregistrer le client si nouveau
            if (!empty($data['client_phone'])) {
                Client::firstOrCreate(
                    ['carwash_id' => $carwashId, 'phone' => $data['client_phone']],
                    [
                        'name' => $data['client_name'] ?? 'Client',
                        'vehicle_brand' => $data['vehicle_brand'],
                        'vehicle_plate' => $data['vehicle_plate'],
                    ]
                );
            }

            // Suivi fidélité par plaque d'immatriculation
            $loyaltyVisit = LoyaltyVisit::firstOrCreate(
                ['carwash_id' => $carwashId, 'vehicle_plate' => strtoupper($data['vehicle_plate'])],
                ['client_name' => $data['client_name'] ?? null, 'client_phone' => $data['client_phone'] ?? null]
            );
            $loyaltyVisit->increment('visits_count');
            $loyaltyVisit->update(['last_visit_at' => now()]);
        });

        return back()->with('success', 'Facture créée et service démarré.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return back()->with('success', 'Facture supprimée.');
    }
}
