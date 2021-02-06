<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'streetname',
        'streetnumber',
        'plz',
        'city',
        'IBAN',
        'checks',
        'roles'
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
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class, 'user_id', 'id', 'id', 'plan_id');
    }

    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }
}
