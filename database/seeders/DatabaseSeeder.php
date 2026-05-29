<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Carwash;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Equipment;
use App\Models\LoyaltyConfig;
use App\Models\LoyaltyVisit;
use App\Models\SmsConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'first_name' => 'Super', 'last_name' => 'Admin',
            'email' => 'admin@carwash.com',
            'password' => Hash::make('password'),
            'role' => 'admin', 'is_active' => true,
            'currency' => 'EUR', 'language' => 'fr',
        ]);

        // Centres
        $carwash1 = Carwash::create([
            'name' => 'AutoSplash Premium', 'address' => '15 Avenue des Champs-Élysées',
            'city' => 'Paris', 'postal_code' => '75008',
            'phone' => '+33 1 23 45 67 89', 'email' => 'contact@autosplash.fr', 'is_active' => true,
        ]);
        $carwash2 = Carwash::create([
            'name' => 'CleanCar Express', 'address' => '42 Rue de la République',
            'city' => 'Lyon', 'postal_code' => '69001',
            'phone' => '+33 4 78 90 12 34', 'email' => 'info@cleancar.fr', 'is_active' => true,
        ]);

        // Users centre 1
        $manager1 = User::create([
            'first_name' => 'Jean', 'last_name' => 'Dupont',
            'email' => 'manager@autosplash.fr', 'password' => Hash::make('password'),
            'role' => 'manager', 'carwash_id' => $carwash1->id,
            'is_active' => true, 'currency' => 'EUR', 'language' => 'fr',
        ]);
        User::create([
            'first_name' => 'Marie', 'last_name' => 'Martin',
            'email' => 'reception@autosplash.fr', 'password' => Hash::make('password'),
            'role' => 'receptionist', 'carwash_id' => $carwash1->id,
            'is_active' => true, 'currency' => 'EUR', 'language' => 'fr',
        ]);
        $carwash1->update(['manager_id' => $manager1->id]);

        // Employés
        $employees = [];
        foreach ([
            ['first_name'=>'Ahmed','last_name'=>'Benali','phone'=>'+33 6 11 22 33 44','salary_type'=>'commission','commission_rate'=>30],
            ['first_name'=>'Pierre','last_name'=>'Lefebvre','phone'=>'+33 6 55 66 77 88','salary_type'=>'commission','commission_rate'=>25],
            ['first_name'=>'Kofi','last_name'=>'Mensah','phone'=>'+33 6 99 00 11 22','salary_type'=>'hourly','hourly_rate'=>12.50],
        ] as $i => $emp) {
            $employees[] = Employee::create(array_merge($emp, [
                'code' => 'EMP'.str_pad($i+1,3,'0',STR_PAD_LEFT),
                'carwash_id' => $carwash1->id, 'is_active' => true,
                'total_cars_washed' => rand(10,50), 'total_earnings' => rand(200,800),
            ]));
        }

        // Services
        $services = [];
        foreach ([
            ['name'=>'Lavage Extérieur','price'=>10.00,'duration'=>20],
            ['name'=>'Lavage Intérieur + Extérieur','price'=>20.00,'duration'=>45],
            ['name'=>'Nettoyage Complet Premium','price'=>35.00,'duration'=>90],
            ['name'=>'Polish & Cire','price'=>50.00,'duration'=>120],
            ['name'=>'Désinfection Habitacle','price'=>25.00,'duration'=>30],
        ] as $svc) {
            $services[] = Service::create(array_merge($svc, ['carwash_id'=>$carwash1->id,'is_active'=>true]));
        }

        // Clients
        $clients = [
            ['name'=>'Thomas Bernard','phone'=>'+33 6 10 20 30 40','vehicle_brand'=>'Renault','vehicle_plate'=>'AB-123-CD'],
            ['name'=>'Sophie Girard','phone'=>'+33 6 50 60 70 80','vehicle_brand'=>'Peugeot','vehicle_plate'=>'EF-456-GH'],
            ['name'=>'Lucas Moreau','phone'=>'+33 6 90 80 70 60','vehicle_brand'=>'BMW','vehicle_plate'=>'IJ-789-KL'],
            ['name'=>'Amina Diallo','phone'=>'+33 6 12 34 56 78','vehicle_brand'=>'Mercedes','vehicle_plate'=>'MN-321-OP'],
        ];
        foreach ($clients as $c) {
            Client::create(array_merge($c, ['carwash_id'=>$carwash1->id]));
        }

        // Factures (sur plusieurs jours pour les stats)
        $invoicePlates = [
            ['brand'=>'Renault','plate'=>'AB-123-CD','phone'=>'+33 6 10 20 30 40','name'=>'Thomas Bernard','days_ago'=>0],
            ['brand'=>'Peugeot','plate'=>'EF-456-GH','phone'=>'+33 6 50 60 70 80','name'=>'Sophie Girard','days_ago'=>0],
            ['brand'=>'BMW','plate'=>'IJ-789-KL','phone'=>'+33 6 90 80 70 60','name'=>'Lucas Moreau','days_ago'=>1],
            ['brand'=>'Mercedes','plate'=>'MN-321-OP','phone'=>'+33 6 12 34 56 78','name'=>'Amina Diallo','days_ago'=>2],
            ['brand'=>'Renault','plate'=>'AB-123-CD','phone'=>'+33 6 10 20 30 40','name'=>'Thomas Bernard','days_ago'=>3],
            ['brand'=>'Toyota','plate'=>'QR-654-ST','phone'=>null,'name'=>'Client','days_ago'=>4],
            ['brand'=>'Peugeot','plate'=>'EF-456-GH','phone'=>'+33 6 50 60 70 80','name'=>'Sophie Girard','days_ago'=>5],
            ['brand'=>'BMW','plate'=>'IJ-789-KL','phone'=>'+33 6 90 80 70 60','name'=>'Lucas Moreau','days_ago'=>7],
        ];

        foreach ($invoicePlates as $i => $inv) {
            $service  = $services[$i % count($services)];
            $employee = $employees[$i % count($employees)];
            $commission = $service->price * ($employee->commission_rate / 100);
            $date = now()->subDays($inv['days_ago']);

            $invoice = Invoice::create([
                'carwash_id'         => $carwash1->id,
                'client_name'        => $inv['name'],
                'client_phone'       => $inv['phone'],
                'vehicle_brand'      => $inv['brand'],
                'vehicle_plate'      => $inv['plate'],
                'service_id'         => $service->id,
                'service_name'       => $service->name,
                'service_price'      => $service->price,
                'employee_id'        => $employee->id,
                'employee_commission'=> $commission,
                'total_amount'       => $service->price,
                'status'             => 'paid',
                'invoice_number'     => Invoice::generateNumber($carwash1->id),
                'created_at'         => $date,
                'updated_at'         => $date,
            ]);

            // Loyalty tracking
            LoyaltyVisit::updateOrCreate(
                ['carwash_id'=>$carwash1->id, 'vehicle_plate'=>strtoupper($inv['plate'])],
                ['client_name'=>$inv['name'],'client_phone'=>$inv['phone'],'last_visit_at'=>$date]
            );
            LoyaltyVisit::where('carwash_id',$carwash1->id)->where('vehicle_plate',strtoupper($inv['plate']))->increment('visits_count');
        }

        // Rendez-vous
        foreach ([
            ['name'=>'Thomas Bernard','phone'=>'+33 6 10 20 30 40','brand'=>'Renault','plate'=>'AB-123-CD','status'=>'scheduled'],
            ['name'=>'Sophie Girard','phone'=>'+33 6 50 60 70 80','brand'=>'Peugeot','plate'=>'EF-456-GH','status'=>'in_progress'],
            ['name'=>'Lucas Moreau','phone'=>'+33 6 90 80 70 60','brand'=>'BMW','plate'=>'IJ-789-KL','status'=>'completed'],
        ] as $i => $apt) {
            $service = $services[$i % count($services)];
            Appointment::create([
                'carwash_id' => $carwash1->id,
                'client_name' => $apt['name'], 'client_phone' => $apt['phone'],
                'vehicle_brand' => $apt['brand'], 'vehicle_plate' => $apt['plate'],
                'service_id' => $service->id, 'service_name' => $service->name,
                'employee_id' => $employees[$i % count($employees)]->id,
                'appointment_date' => now()->toDateString(),
                'appointment_time' => sprintf('%02d:00', 9+$i*2),
                'status' => $apt['status'],
            ]);
        }

        // Dépenses de démonstration
        $expenseData = [
            ['type'=>'electricity','amount'=>180.00,'description'=>'Facture EDF — mai','expense_date'=>now()->subDays(5)->toDateString()],
            ['type'=>'water','amount'=>95.50,'description'=>'Consommation eau','expense_date'=>now()->subDays(8)->toDateString()],
            ['type'=>'products','amount'=>220.00,'description'=>'Produits nettoyage — stock mensuel','expense_date'=>now()->subDays(3)->toDateString()],
            ['type'=>'maintenance','amount'=>350.00,'description'=>'Réparation machine haute pression','expense_date'=>now()->subDays(12)->toDateString()],
            ['type'=>'other','amount'=>60.00,'description'=>'Fournitures bureau','expense_date'=>now()->subDays(1)->toDateString()],
            ['type'=>'electricity','amount'=>165.00,'description'=>'Facture EDF — avril','expense_date'=>now()->subDays(35)->toDateString()],
            ['type'=>'water','amount'=>88.00,'description'=>'Consommation eau — avril','expense_date'=>now()->subDays(38)->toDateString()],
        ];
        foreach ($expenseData as $exp) {
            Expense::create(array_merge($exp, ['carwash_id'=>$carwash1->id,'created_by'=>$manager1->id]));
        }

        // Équipements de démonstration
        $equipData = [
            ['name'=>'Machine à laver principale','type'=>'washing_machine','purchase_date'=>'2023-01-15','cost'=>4500.00,'status'=>'available'],
            ['name'=>'Aspirateur industriel X200','type'=>'vacuum','purchase_date'=>'2023-03-10','cost'=>850.00,'status'=>'available'],
            ['name'=>'Compresseur air 50L','type'=>'compressor','purchase_date'=>'2022-09-20','cost'=>600.00,'status'=>'maintenance','notes'=>'Révision annuelle en cours'],
            ['name'=>'Haute pression Kärcher HD 5/15','type'=>'pressure_washer','purchase_date'=>'2023-06-01','cost'=>1200.00,'status'=>'available'],
            ['name'=>'Aspirateur portable','type'=>'vacuum','purchase_date'=>'2024-01-05','cost'=>180.00,'status'=>'broken','notes'=>'Moteur grillé — à remplacer'],
        ];
        foreach ($equipData as $eq) {
            Equipment::create(array_merge($eq, ['carwash_id'=>$carwash1->id]));
        }

        // Config fidélité
        LoyaltyConfig::create([
            'carwash_id' => $carwash1->id,
            'required_visits' => 10,
            'discount_percentage' => 15.00,
            'is_active' => true,
        ]);

        // Config SMS
        SmsConfig::create([
            'carwash_id' => $carwash1->id,
            'provider' => 'custom',
            'sender_name' => 'AutoSplash',
            'auto_send' => false,
        ]);

        $this->command->info('✅ Données de démonstration créées !');
        $this->command->info('   Admin:         admin@carwash.com / password');
        $this->command->info('   Manager:       manager@autosplash.fr / password');
        $this->command->info('   Réceptionniste:reception@autosplash.fr / password');
    }
}
