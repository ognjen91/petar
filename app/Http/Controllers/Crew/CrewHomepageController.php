<?php

namespace App\Http\Controllers\Crew;

use Illuminate\Http\Request;
use App\Models\Excursion;
use App\Models\Station;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class CrewHomepageController extends Controller
{
    public function __invoke(Request $request){
        $date = !$request->date? Carbon::today() : Carbon::parse($request->date);

        $todaysExcursions = Excursion::whereDate('departure', $date)->crewCanSee()->orderBy('departure', 'asc')->get();

        $excursions = [];
        foreach($todaysExcursions as $excursion){
            $excursions[] = [
                'excursionName' => $excursion->name,
                'excursionId' => $excursion->id,
                'reservations' => $excursion->reservations->groupBy(function ($reservation, $key) {
                                                                    return $reservation->station->name;
                                                                 })
            ];
        }
        // dd($excursions);
    // $excursions[$excursion->name] = $excursion->reservations->groupBy(function ($reservation, $key) {
    //     return $reservation->station->name;
    // });
        
        
        
        // dates for select date
        $today = Carbon::today();
        $dates = [];
        for($i=0; $i<=9; $i++){
            $dates[] = [
                'value' => $today->copy()->addDays($i)->format('d-m-Y'),
                'label' => $today->copy()->addDays($i)->format('d.m.Y.')
            ];
        }

        return view('crew.homepage', compact('excursions', 'dates'));
    }
}
