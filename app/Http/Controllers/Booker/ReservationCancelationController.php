<?php

namespace App\Http\Controllers\Booker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\PrivateExcursionReservation;
use Carbon\Carbon;

class ReservationCancelationController extends Controller
{
    public function __invoke(Request $request, $reservation){
        $reservation = $request->excursion_type == 'regular'? Reservation::find($reservation) : PrivateExcursionReservation::find($reservation);

        if($reservation->booker_id !== auth()->user()->id){
                return response()->json([
                        'status' => 'Greška! Ne možete otkazati tuđu rezervaciju'
                ], 501); 
            }
        
        if($reservation->isCanceled){
                return response()->json([
                        'status' => 'Greška! Rezervacija je već otkazana.'
                ], 501); 
        }
        
        if($request->excursion_type == 'regular'){
                if(Carbon::parse($reservation->excursion->departure) < Carbon::now()){
                        return response()->json([
                                'status' => 'Greška! Polazak je već realizovan.'
                        ], 501); 
                }
        }

            
        $reservation->update([
                'active' => 0,
                'cancelation_time' => Carbon::now()->toDateTimeString()
        ]);
        
        // CANCEL RETURN WAY RESERVATION AS WELL, IF EXISTS
        if($reservation->returnDirectionReservation){
                $reservation->returnDirectionReservation->update([
                        'active' => 0,
                        'cancelation_time' => Carbon::now()->toDateTimeString()
                ]);   
        }
        
        //update total_seats of the regular excursion
        if($request->excursion_type == 'regular'){
                $reservation->excursion->total_seats += $reservation->seats;
                $reservation->excursion->total_child_seats += $reservation->child_seats;
                $reservation->excursion->save();
                
                // FOR RETURN WAY AS WELL, IF NOT ALREADY CANCELED!
                if ($reservation->returnDirectionReservation) {
                        if(!$reservation->returnDirectionReservation->isCanceled){
                                $reservation->returnDirectionReservation->excursion->total_seats += $reservation->seats;
                                $reservation->returnDirectionReservation->excursion->total_child_seats += $reservation->child_seats;
                                $reservation->returnDirectionReservation->excursion->save();
                        }
                }
                
        }

        return response()->json([
                'status' => 'Uspješno otkazano.'
        ], 201); 

    }
}
