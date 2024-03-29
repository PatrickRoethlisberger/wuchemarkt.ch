<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'collection',
        'checks' => 'collection',
    ];

    /**
     * Get the user's display name
     * If available return the company name otherwise first and lastname
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->companyname ? "{$this->companyname}" : "{$this->firstname} {$this->lastname}";
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function lastOrder()
    {
        return $this->orders()->latest()->first();
    }

    public function currentOrder()
    {
        return $this->orders()->where('from', '<=', now())->where('until', '>=', now())->orderby('until', 'desc')->first();
    }

    public function order($date)
    {
        return $this->orders()->where('from', '<=', $date)->where('until', '>=', $date)->orderby('until', 'desc')->first();
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'provider_user_id');
    }

    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }

    public function nextOccupation()
    {
        return $this->occupations()->where('date', '>=', Carbon::today())->orderby('date', 'asc')->first();
    }

    public function audits()
    {
        return $this->hasMany(Audit::class, 'client_user_id', 'id');
    }

    public function audited()
    {
        return $this->hasMany(Audit::class, 'auditor_user_id', 'id');
    }
}
