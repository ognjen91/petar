<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['booker_id', 'excursion_id', 'seats', 'station_id', 'price', 'active'];


    /**
     * RELATIONSHIPS
     */

    public function excursion(){
        return $this->belongsTo(Excursion::class, 'excursion_id');
    }
    
    public function station(){
        return $this->belongsTo(Station::class);
    }
    
    public function booker(){
        return $this->belongsTo(User::class, 'booker_id');
    }

    /**
     * SCOPES
     */

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }




}
