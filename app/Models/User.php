<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    protected static function boot()
    {
        parent::boot();
        
        // DEFAULT ROLE
        static::created(function ($user) {
            $user->assignRole('Booker');
        });
    }



    /**
     * RELATIONSHIPS
     */

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'booker_id')->whereNull('first_direction_reservation_id'); //first way only
        // return $this->hasMany(Reservation::class, 'booker_id')->whereNull('first_direction_reservation_id'); //first way only
    }
        
    public function privateExcursionReservations()
    {
        return $this->hasMany(PrivateExcursionReservation::class, 'booker_id');
    }


    /**
     * ACCESSORS
     */

    public function getIsSuperAdminAttribute($query)
    {
        return $this->hasRole('Super Admin');
    }
}
