<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'phone', 'role', 'carwash_id', 'is_active',
        'currency', 'language', 'subscription_status', 'subscription_date',
        'identity_document', 'identity_verified',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'is_active'          => 'boolean',
            'identity_verified'  => 'boolean',
            'subscription_date'  => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isManager(): bool { return $this->role === 'manager'; }
    public function isReceptionist(): bool { return $this->role === 'receptionist'; }
    public function isEmployee(): bool { return $this->role === 'employee'; }
    public function isCommissionnaire(): bool { return $this->role === 'commissionnaire'; }

    public function referredCarwashes()
    {
        return $this->hasMany(Carwash::class, 'referred_by');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'commissionnaire_id');
    }
}
