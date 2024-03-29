<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ExcursionType;
use App\Models\PrivateExcursionReservation;
use Carbon\Carbon;
use App\Http\Resources\ExcursionTypeResource;

use Illuminate\Database\Eloquent\Builder;


class BookerPrivateExcursionsReservationsController extends Controller
{
    public function index(Request $request){

        $reservations = PrivateExcursionReservation::where('booker_id', auth()->user()->id);
        
        
        $startDate = $request->startDate? Carbon::parse($request->startDate)->format('Y-m-d') : null;
        $endDate = $request->endDate? Carbon::parse($request->endDate)->format('Y-m-d') : null;
        
        //if start date is set
        if($startDate) $reservations = $reservations->where('start', '>=' , $request->startDate);
        
        //if end date is set
        if($endDate) $reservations = $reservations->where('end', '<=' , $request->endDate);
        
        $reservationsHelper = clone $reservations;
        $totalPrice = $reservationsHelper->active()->sum('price');
        // dd($totalPrice);

        $reservations = $reservations->orderBy('start', 'desc')->get();
        
        

        return view('booker.my-reservations.private.index', compact('reservations', 'startDate', 'endDate', 'totalPrice'));
    }

    public function show(Request $request, PrivateExcursionReservation $reservation){
        return view('booker.my-reservations.private.show', compact('reservation'));
    }
}
