<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'carwash_id', 'type', 'amount', 'description', 'expense_date', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public static array $typeLabels = [
        'electricity' => 'Électricité',
        'water'       => 'Eau',
        'products'    => 'Produits',
        'maintenance' => 'Maintenance',
        'salary'      => 'Salaires',
        'other'       => 'Autre',
    ];

    public static array $typeBadges = [
        'electricity' => 'badge-yellow',
        'water'       => 'badge-blue',
        'products'    => 'badge-green',
        'maintenance' => 'badge-purple',
        'salary'      => 'badge-red',
        'other'       => 'badge-gray',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
