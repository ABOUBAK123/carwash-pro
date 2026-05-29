<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $appointments = Appointment::where('carwash_id', $carwashId)
            ->with('employee', 'service')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        $employees = Employee::where('carwash_id', $carwashId)->where('is_active', true)->get();
        $services = Service::where('carwash_id', $carwashId)->where('is_active', true)->get();

        return view('appointments.index', compact('appointments', 'employees', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:200',
            'client_phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'nullable|string|max:100',
            'vehicle_plate' => 'nullable|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'employee_id' => 'nullable|exists:employees,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $carwashId = Auth::user()->carwash_id;
        $data['carwash_id'] = $carwashId;

        if (!empty($data['service_id'])) {
            $service = Service::find($data['service_id']);
            $data['service_name'] = $service->name;
        }

        $appointment = Appointment::create($data);

        // Notification SMS de confirmation
        if (!empty($data['client_phone'])) {
            SmsNotification::create([
                'phone_number' => $data['client_phone'],
                'message' => "Bonjour {$data['client_name']}, votre RDV est confirmé pour le {$data['appointment_date']} à {$data['appointment_time']}.",
                'type' => 'appointment',
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Rendez-vous créé.');
    }

    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);

        if ($appointment->client_phone) {
            SmsNotification::create([
                'phone_number' => $appointment->client_phone,
                'message' => "Bonjour {$appointment->client_name}, votre véhicule ({$appointment->vehicle_brand} - {$appointment->vehicle_plate}) est prêt. Merci !",
                'type' => 'service_complete',
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Service marqué comme terminé.');
    }

    public function startService(Appointment $appointment)
    {
        $appointment->update(['status' => 'in_progress']);

        if ($appointment->client_phone) {
            SmsNotification::create([
                'phone_number' => $appointment->client_phone,
                'message' => "Bonjour {$appointment->client_name}, le lavage de votre véhicule ({$appointment->vehicle_plate}) a commencé.",
                'type' => 'service_start',
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Service démarré.');
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Rendez-vous supprimé.');
    }
}
