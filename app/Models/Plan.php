<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'slug', 'name', 'description',
        'price_monthly_xof', 'price_monthly_eur',
        'max_employees', 'max_invoices', 'trial_days',
        'badge', 'color', 'features', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features'          => 'array',
        'is_active'         => 'boolean',
        'price_monthly_xof' => 'decimal:2',
        'price_monthly_eur' => 'decimal:2',
    ];

    public static function ordered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderBy('sort_order')->get();
    }

    public static function paid(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('price_monthly_xof', '>', 0)->orderBy('sort_order')->get();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    public function maxEmployeesLabel(): string
    {
        return $this->max_employees === -1 ? 'Illimité' : (string) $this->max_employees;
    }

    public function maxInvoicesLabel(): string
    {
        return $this->max_invoices === -1 ? 'Illimité' : (string) $this->max_invoices;
    }
}
