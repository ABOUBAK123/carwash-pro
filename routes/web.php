<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmsConfigController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\TermsSettingController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AdminCommissionnaireController;
use App\Http\Controllers\CommissionnaireController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn() => redirect()->route('pricing'));
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware(['auth', 'role'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Abonnement (all roles with carwash)
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');

    // Commissionnaire
    Route::middleware('role:commissionnaire')->prefix('commissionnaire')->name('commissionnaire.')->group(function () {
        Route::get('/dashboard', [CommissionnaireController::class, 'dashboard'])->name('dashboard');
        Route::post('/create-center', [CommissionnaireController::class, 'createCenter'])->name('create-center');
    });

    // Profile (all roles)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/carwashes', [AdminController::class, 'carwashes'])->name('carwashes');
        Route::post('/carwashes', [AdminController::class, 'createCarwash'])->name('carwashes.store');
        Route::patch('/carwashes/{carwash}/toggle', [AdminController::class, 'toggleCarwash'])->name('carwashes.toggle');
        Route::delete('/carwashes/{carwash}', [AdminController::class, 'deleteCarwash'])->name('carwashes.delete');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::patch('/users/{user}/assign-carwash', [AdminController::class, 'assignCarwash'])->name('users.assign-carwash');

        // Inscriptions
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations');
        Route::patch('/registrations/{registration}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
        Route::patch('/registrations/{registration}/reject', [RegistrationController::class, 'reject'])->name('registrations.reject');
        Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');

        // Devises
        Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies');
        Route::post('/currencies', [CurrencyController::class, 'store'])->name('currencies.store');
        Route::patch('/currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
        Route::patch('/currencies/{currency}/toggle', [CurrencyController::class, 'toggle'])->name('currencies.toggle');
        Route::delete('/currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');

        // Paramètres système
        Route::get('/payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings');
        Route::patch('/payment-settings', [PaymentSettingController::class, 'update'])->name('payment-settings.update');

        Route::get('/email-settings', [EmailSettingController::class, 'index'])->name('email-settings');
        Route::patch('/email-settings', [EmailSettingController::class, 'update'])->name('email-settings.update');
        Route::post('/email-settings/test', [EmailSettingController::class, 'test'])->name('email-settings.test');

        Route::get('/terms-settings', [TermsSettingController::class, 'index'])->name('terms-settings');
        Route::patch('/terms-settings', [TermsSettingController::class, 'update'])->name('terms-settings.update');

        // Plans d'abonnement
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans');
        Route::patch('/plans/{plan}', [AdminPlanController::class, 'update'])->name('plans.update');
        Route::patch('/plans/{plan}/toggle', [AdminPlanController::class, 'toggleActive'])->name('plans.toggle');

        // Commissionnaires
        Route::get('/commissionnaires', [AdminCommissionnaireController::class, 'index'])->name('commissionnaires');
        Route::post('/commissionnaires', [AdminCommissionnaireController::class, 'store'])->name('commissionnaires.store');
        Route::patch('/commissionnaires/{user}/toggle', [AdminCommissionnaireController::class, 'toggle'])->name('commissionnaires.toggle');
        Route::patch('/commissionnaires/{user}/verify', [AdminCommissionnaireController::class, 'verify'])->name('commissionnaires.verify');
        Route::patch('/commissionnaires/{user}/mark-paid', [AdminCommissionnaireController::class, 'markPaid'])->name('commissionnaires.mark-paid');
        Route::delete('/commissionnaires/{user}', [AdminCommissionnaireController::class, 'destroy'])->name('commissionnaires.destroy');
        Route::get('/commissionnaires/{user}/document', [AdminCommissionnaireController::class, 'downloadDocument'])->name('commissionnaires.document');
    });

    // Manager + Receptionist + Admin
    Route::middleware('role:admin,manager,receptionist')->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::patch('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
        Route::patch('/appointments/{appointment}/start', [AppointmentController::class, 'startService'])->name('appointments.start');
        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    });

    // Manager + Admin only
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::patch('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::patch('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('/salaries', [SalaryController::class, 'index'])->name('salary.index');

        // Dépenses
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

        // Équipements
        Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::patch('/equipment/{equipment}/status', [EquipmentController::class, 'updateStatus'])->name('equipment.status');
        Route::delete('/equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');

        // Profits
        Route::get('/profits', [ProfitController::class, 'index'])->name('profits.index');

        // Performance
        Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');

        // Fidélité
        Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::patch('/loyalty/configure', [LoyaltyController::class, 'configure'])->name('loyalty.configure');
        Route::patch('/loyalty/{visit}/reset', [LoyaltyController::class, 'resetVisit'])->name('loyalty.reset');

        // Config SMS
        Route::get('/sms/config', [SmsConfigController::class, 'index'])->name('sms.config');
        Route::patch('/sms/config', [SmsConfigController::class, 'update'])->name('sms.update');
    });
});
