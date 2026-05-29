<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'first_name', 'last_name', 'phone', 'email',
        'carwash_id', 'salary_type', 'hourly_rate', 'fixed_salary',
        'commission_rate', 'total_cars_washed', 'total_earnings', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'fixed_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public static function generateCode(int $carwashId): string
    {
        $count = static::where('carwash_id', $carwashId)->count() + 1;
        return 'EMP' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
