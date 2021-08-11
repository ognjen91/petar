<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ExcursionType;
use Carbon\Carbon;
use App\Http\Resources\ExcursionTypeResource;

use Illuminate\Database\Eloquent\Builder;


class BookerRegularExcursionsReservationsController extends Controller
{
    public function index(Request $request){
        $excursionTypes = ExcursionTypeResource::collection(ExcursionType::all())->resolve();

        $reservations = Reservation::where('booker_id', auth()->user()->id);

        $request->startDate = $request->startDate?? '2021-07-01';
        $request->endDate = $request->endDate?? '2021-12-31';

        $excursionType = $request->excursionType? ExcursionType::find($request->excursionType) : null;
        $startDate = Carbon::parse($request->startDate)->format('Y-m-d');
        $endDate = Carbon::parse($request->endDate)->format('Y-m-d');

        //if start date is set
        if($request->startDate) $reservations = $reservations->whereHas('excursion', function (Builder $query) use ($request) {
            $query->where('departure', '>=', $request->startDate);
        });

        //if end date is set
        if($request->endDate) $reservations = $reservations->whereHas('excursion', function (Builder $query) use ($request) {
            $query->where('departure', '<=', $request->endDate);
        });

        //if excursion type is set
        if($request->excursionType) $reservations = $reservations->whereHas('excursion', function (Builder $query) use ($request) {
            $query->where('excursion_type_id',  $request->excursionType);
        });

        $reservationsHelper = clone $reservations;
        $totalPrice = $reservationsHelper->active()->sum('price');
        $totalSeats = $reservationsHelper->active()->sum('seats');
        
        $reservations = $reservations->orderBy('created_at', 'desc')->get();

        if($request->order == 'asc'){
            $reservations = $reservations->sortBy(function ($reservation, $key) {
                return $reservation->excursion->departure;
            });
        }else{
            $reservations = $reservations->sortByDesc(function ($reservation, $key) {
                return $reservation->excursion->departure;
            });
        }



        return view('booker.my-reservations.regular.index', compact('reservations', 'excursionType', 'startDate', 'endDate', 'totalPrice', 'totalSeats', 'excursionTypes'));
    }

    public function show(Request $request, Reservation $reservation){
        $returnDirectionReservation = $reservation->returnDirectionReservation;

        return view('booker.my-reservations.regular.show', compact('reservation', 'returnDirectionReservation'));
    }
}
