<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'carwash_id', 'name', 'type', 'purchase_date', 'cost', 'status', 'notes',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public static array $typeLabels = [
        'washing_machine'  => 'Machine à laver',
        'vacuum'           => 'Aspirateur',
        'compressor'       => 'Compresseur',
        'pressure_washer'  => 'Haute pression',
        'other'            => 'Autre',
    ];

    public static array $statusLabels = [
        'available'   => 'Disponible',
        'maintenance' => 'En maintenance',
        'broken'      => 'Hors service',
    ];

    public static array $statusBadges = [
        'available'   => 'badge-green',
        'maintenance' => 'badge-yellow',
        'broken'      => 'badge-red',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }
}
