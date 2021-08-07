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
                            'message',
                            'first_direction_reservation_id'
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

    // CONNECTED RESERVATION (OF THE RETURN DIRECTION WAY)
    public function returnDirectionReservation(){
        return $this->hasOne(Reservation::class, 'first_direction_reservation_id');
    }
    
    // CONNECTED RESERVATION VICE VERSA RELATIONSHIP
    public function firstDirectionReservation(){
        return $this->belongsTo(Reservation::class, 'first_direction_reservation_id');
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

     
    public function getIsCanceledAttribute(){
        return !!$this->cancelation_time;
    }

     public function getDepartureAttribute(){
         return $this->excursion->departure;
     }

     public function getIsInFutureAttribute(){
         return Carbon::parse($this->excursion->departure) > Carbon::now();
     }

     public function getIsReturnWayDirectionReservationAttribute(){
         return !!$this->first_direction_reservation_id;
     }




}
