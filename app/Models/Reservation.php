<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
                            'booker_id', 
                            'excursion_id', 
                            'seats', 
                            'station_id', 
                            'price', 
                            'active', 
                            'cancelation_time',
                            'child_seats',
                            'message'
                        ];

    protected static function boot() {

        parent::boot();
        
        // SAVE OLD VALUE OF SEATS
        static::created(function ($reservation) {
            if($reservation->child_seats){
                $reservation->excursion->total_child_seats += $reservation->child_seats;
                $reservation->excursion->save();
            }
        });
    }

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

    /**
     * ACCESSORS
     */

     public function getIsInFutureAttribute(){
         return Carbon::parse($this->excursion->departure) > Carbon::now();
     }




}
