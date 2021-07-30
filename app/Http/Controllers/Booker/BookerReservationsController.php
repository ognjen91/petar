<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ExcursionType;
use Carbon\Carbon;
use App\Http\Resources\ExcursionTypeResource;

use Illuminate\Database\Eloquent\Builder;


class BookerReservationsController extends Controller
{
    public function index(Request $request){
        $excursionTypes = ExcursionTypeResource::collection(ExcursionType::all())->resolve();

        $reservations = Reservation::where('booker_id', auth()->user()->id);

        $request->startDate = $request->startDate?? '2021-07-01';
        $request->endDate = $request->endDate?? '2021-12-31';

        $excursionType = $request->excursionType? ExcursionType::find($request->excursionType) : null;
        $startDate = $request->startDate? Carbon::parse($request->startDate)->format('Y-m-d') : '2021-12-31';
        $endDate = $request->endDate? Carbon::parse($request->endDate)->format('Y-m-d') : null;

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

        $reservations = $reservations->orderBy('created_at', 'desc')->paginate(50);

        $totalPrice = $reservations->sum('price');
        $totalSeats = $reservations->sum('seats');

        return view('booker.my-reservations.index', compact('reservations', 'excursionType', 'startDate', 'endDate', 'totalPrice', 'totalSeats', 'excursionTypes'));
    }

    public function show(Request $request, Reservation $reservation){
        return view('booker.my-reservations.show', compact('reservation'));
    }
}
