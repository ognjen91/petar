<?php

namespace App\Http\Controllers\Booker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationCancelationController extends Controller
{
    public function __invoke(Reservation $reservation){
        if($reservation->booker_id !== auth()->user()->id){
                return response()->json([
                        'status' => 'Greška! Ne možete otkazati tuđu rezervaciju'
                ], 501); 
            }
        if(Carbon::parse($reservation->excursion->departure) < Carbon::now()){
                return response()->json([
                        'status' => 'Greška! Polazak je već realizovan.'
                ], 501); 
        }

        if(!$reservation->active){
                return response()->json([
                        'status' => 'Greška! Rezervacija je već otkazana.'
                ], 501); 
            }
            
        $reservation->update([
                'active' => 0,
                'cancelation_time' => Carbon::now()->toDateTimeString()
        ]);

        //update total_seats of the excursion
        $reservation->excursion->total_seats += $reservation->seats;
        $reservation->excursion->save();

        return response()->json([
                'status' => 'Uspješno otkazano.'
        ], 201); 

    }
}
