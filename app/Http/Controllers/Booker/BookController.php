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

        $excursion = Excursion::find($request->selectedExcursionId);
        if($excursion->freeSeats < $request->seats){
            return response()->json([
                'status' => 'Greška! Broj slobodnih mjesta je u međuvremenu smanjen. Molimo da odaberete drugi broj i pokušate ponovo'
            ], 501); 
        }

        $excursion->reservations()->create([
            'booker_id' => 3, //todo : add the logic,
            'seats' => $request->seats,
            'station_id' => $request->station,
            'price' => $request->price
        ]);

        return response()->json([
            'status' => 'Uspješno rezerivsano'
        ], 201); 

    }
}
