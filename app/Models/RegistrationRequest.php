<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationRequest extends Model
{
    protected $fillable = [
        'center_name', 'owner_name', 'email', 'phone', 'address', 'city',
        'latitude', 'longitude', 'description', 'services',
        'status', 'terms_accepted', 'terms_accepted_at',
        'approved_at', 'rejected_at',
    ];

    protected $casts = [
        'terms_accepted'    => 'boolean',
        'terms_accepted_at' => 'datetime',
        'approved_at'       => 'datetime',
        'rejected_at'       => 'datetime',
    ];

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public static array $statusBadges = [
        'pending'  => 'badge-yellow',
        'approved' => 'badge-green',
        'rejected' => 'badge-red',
    ];

    public static array $statusLabels = [
        'pending'  => 'En attente',
        'approved' => 'Approuvé',
        'rejected' => 'Rejeté',
    ];
}
