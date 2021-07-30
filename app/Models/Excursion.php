<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excursion extends Model
{
    use HasFactory;

    protected $fillable = ['excursion_type_id', 'departure', 'total_seats'];

    protected static function boot() {

        parent::boot();
        
        // SAVE OLD VALUE OF SEATS
        static::updating(function ($excursion) {

            //if new number is less than number of free seats
            $totalSeatsReserved = $excursion->reservations->sum('seats');

            if($excursion->total_seats < $totalSeatsReserved){
                $excursion->total_seats = $excursion->getOriginal('total_seats');
                throw new \Exception('Ukupan broj mjesta ne može biti manji od broja prethodno rezervisanih (broj rezerivsanih:' . $totalSeatsReserved . ')');
                return;
            }

            //create seatChangeHistories record on seats number change
            if($excursion->total_seats != $excursion->getOriginal('total_seats')) {
                $excursion->seatChangeHistories()->create([
                    'seats_old_value' => $excursion->getOriginal('total_seats'),
                    'seats_new_value' => $excursion->total_seats
                ]);
            }
        });
    }

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

     public function seatChangeHistories(){
         return $this->hasMany(SeatChangeHistory::class);
     }


    /**
     * ACCESSORS
     */
    public function getFreeSeatsAttribute($query){
        return $this->total_seats - $this->reservations()->active()->get()->sum('seats');
    }

     
}
