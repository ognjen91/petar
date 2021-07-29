<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excursion extends Model
{
    use HasFactory;

    protected $fillable = ['excursion_type_id', 'departure', 'total_seats'];

    /**
     * CASTS
     */

    protected $casts = [
        'departure' => 'datetime'
    ];

    /**
     * RELATIONSHIPS
     */

     public function excursionType(){
         return $this->belongsTo(ExcursionType::class, 'excursion_type_id');
     }

     public function reservations(){
         return $this->hasMany(Reservation::class, 'excursion_id');
     }


    /**
     * ACCESSORS
     */
    public function getFreeSeatsAttribute($query){
        return $this->total_seats - $this->reservations()->active()->get()->sum('seats');
    }

     
}
