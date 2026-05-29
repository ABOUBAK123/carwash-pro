<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carwash extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'city', 'postal_code', 'phone', 'email',
        'latitude', 'longitude', 'manager_id', 'is_active',
        'plan', 'subscription_status', 'trial_ends_at', 'subscription_ends_at',
        'referred_by',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'trial_ends_at'       => 'datetime',
        'subscription_ends_at'=> 'datetime',
    ];

    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial'
            && $this->trial_ends_at
            && now()->isBefore($this->trial_ends_at);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active'
            && $this->subscription_ends_at
            && now()->isBefore($this->subscription_ends_at);
    }

    public function subscriptionExpired(): bool
    {
        return in_array($this->subscription_status, ['expired', 'cancelled'])
            || ($this->subscription_status === 'trial' && $this->trial_ends_at && now()->isAfter($this->trial_ends_at))
            || ($this->subscription_status === 'active' && $this->subscription_ends_at && now()->isAfter($this->subscription_ends_at));
    }

    public function daysRemaining(): int
    {
        if ($this->subscription_status === 'trial' && $this->trial_ends_at) {
            return max(0, (int) now()->diffInDays($this->trial_ends_at, false));
        }
        if ($this->subscription_status === 'active' && $this->subscription_ends_at) {
            return max(0, (int) now()->diffInDays($this->subscription_ends_at, false));
        }
        return 0;
    }

    public function planDetails(): array
    {
        return \App\Models\SubscriptionPlan::get($this->plan ?? 'trial');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
