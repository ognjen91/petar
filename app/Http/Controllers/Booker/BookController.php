<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Excursion;

class BookController extends Controller
{
    
    public function __invoke(Request $request){

        // selectedExcursionId : this.selectedExcursionId,
        // seats : this.seats
        // return response()->json([
        //     'status' => $request->all()
        // ], 501); 
        // dd($request->all());

        $excursion = Excursion::find($request->selectedExcursionId);
        if($excursion->freeSeats < $request->seats){
            return response()->json([
                'status' => 'Greška! Broj slobodnih mjesta je u međuvremenu smanjen. Molimo da odaberete drugi broj i pokušate ponovo'
            ], 501); 
        }
        
        if($request->selectedConnectedExcursionId){
            $returnExcursion = Excursion::find($request->selectedConnectedExcursionId);
            if($returnExcursion->freeSeats < $request->seats){
                return response()->json([
                    'status' => 'Greška! Broj slobodnih mjesta za povratni izlet je u međuvremenu smanjen. Molimo da odaberete drugi broj i pokušate ponovo'
                ], 501); 
            }
        }


        $reservation = $excursion->reservations()->create([
            'booker_id' => auth()->user()->id, //todo : add the logic,
            'seats' => $request->seats,
            'child_seats' => $request->child_seats,
            'station_id' => $request->station,
            'price' => $request->price,
            'message' => $request->message
        ]);


        
        // if selectedConnectedExcursionId is set, create reservation for the return way
        
        if(isset($request->selectedConnectedExcursionId)){

            if(!$returnExcursion) return response()->json([
                                             'status' => 'Početni smjer je rezervisan, ali povratni nije pronađen'
                                            ], 501); 

            $reservation->returnDirectionReservation()->create([
                'booker_id' => auth()->user()->id, //todo : add the logic,
                'excursion_id' => $request->selectedConnectedExcursionId,
                'first_direction_reservation_id' => $reservation->id, //previously created reservation
                'seats' => $request->seats,
                'child_seats' => $request->child_seats,
                'station_id' => $returnExcursion->excursionType->stations()->first()->id,
                'price' => 0,
                'message' => '(Rezervacija povratnog smjera)' . $request->message,
            ]);
        }

        return response()->json([
            'status' => 'Uspješno rezerivsano'
        ], 201); 

    }
}
