<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'google_maps_link'];

    /**
     * RELATIONSHIPS
     */

     public function excursionTypes(){
         return $this->belongsToMany(ExcursionType::class, 'excursion_type_station', 'station_id', 'excursion_type_id');
     }

     public function reservations(){
         return $this->hasMany(Reservation::class, 'station_id');
     }
     
}
