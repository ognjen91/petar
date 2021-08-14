<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Mail\ExcursionAlmostFull;


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

            if ($reservation->excursion->freeSeats <= 0.15 * $reservation->excursion->total_seats && !$reservation->excursion->almost_full_notification_sent) {
                // SEND SMS
                try {
                    $basic  = new \Nexmo\Client\Credentials\Basic(config('app.nexmoSmsApiKey'), config('app.nexmoSmsApiSecret'));
                    $client = new \Nexmo\Client($basic);
                    
                    $response = $client->message()->send([
                        'to' => config('app.numberToSendExcursionCapacityAlert'),
                        'from' => 'OSAM',
                        'text' => 'Upozorenje: predjeno je 85% kapaciteta za ' . $reservation->excursion->name
                    ]);

                    $message = $response->current();
                    if ($response->getStatus() == 0){
                        $reservation->excursion->update([
                            'almost_full_notification_sent' => 1
                        ]);
                    }


                } catch (\Exception $e) {
                    //todo: log
                }
                
                // SEND EMAIL
                try {
                    if ($reservation->excursion->freeSeats <= 0.15 * $reservation->excursion->total_seats) {
                        \Mail::to([config('app.excursionAlmostFullEmail1'), config('app.excursionAlmostFullEmail2')])->send(new ExcursionAlmostFull($reservation->excursion));
                    }
                } catch (\Exception $e) {
                    //todo: log
                }
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
        // return $query->where('active', 1);
        return $query->whereNull('cancelation_time');
    }
    
    public function scopeNotReturnWay($query){
        return $query->whereNull('first_direction_reservation_id');
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
