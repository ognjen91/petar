<?php

namespace App\Http\Controllers\Crew;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Station;
use App\Models\Excursion;

class ExcursionStationReservationsController extends Controller
{
    public function __invoke(Request $request, Excursion $excursion, Station $station){
        $reservations = Reservation::where(['excursion_id' => $excursion->id, 'station_id' => $station->id])->get();
        return view('crew.excursion_reservations_on_station', compact('excursion', 'station', 'reservations'));
    }
}
