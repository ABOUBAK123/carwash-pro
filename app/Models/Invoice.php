<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'carwash_id', 'client_name', 'client_phone', 'vehicle_brand', 'vehicle_plate',
        'service_id', 'service_name', 'service_price', 'employee_id',
        'employee_commission', 'total_amount', 'status', 'invoice_number',
    ];

    protected $casts = [
        'service_price' => 'decimal:2',
        'employee_commission' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public static function generateNumber(int $carwashId): string
    {
        $count = static::where('carwash_id', $carwashId)->count() + 1;
        return 'FAC-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
