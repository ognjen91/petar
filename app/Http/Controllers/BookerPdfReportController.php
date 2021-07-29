<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExcursionType;
use App\Models\Excursion;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class BookerPdfReportController extends Controller
{
    public function __invoke(){
        $booker = User::role('Booker')->first();
        $booker = null;

        $excursionType = ExcursionType::first();
        $excursionType = null;

        $startDate = Carbon::parse('2021-07-01')->startOfDay();;
        $endDate = Carbon::parse('2021-07-31')->endOfDay();;

        $formatedStartDate = $startDate? $startDate->format('d.m.Y.') : null;
        $formatedEndDate = $endDate? $endDate->format('d.m.Y.') : null;

        // FORMAT RESERVATIONS
        $reservations = $booker? $booker->reservations() : Reservation::where('seats', '>', 0); //bookers reservations or some default query

        //take in count selected excursion type, if selected
        if($excursionType) $reservations->whereHas('excursion', function (Builder $query) use ($excursionType) {
            $query->where('excursion_type_id', $excursionType->id);
        });

        //take in count start date, if selected
        if($startDate) $reservations = $reservations->whereHas('excursion', function (Builder $query) use ($startDate) {
            $query->where('departure', '>=', $startDate);
        });
    
        //take in count end date, if selected
        if($endDate) $reservations = $reservations->whereHas('excursion', function (Builder $query) use ($endDate) {
            $query->where('departure', '<=', $endDate);
        });

        $reservations = $reservations->get();
        $totalPrice = $reservations->sum('price');
        $totalSeats = $reservations->sum('seats');
        // dd($reservations);

        $data = compact('booker', 'excursionType', 'formatedStartDate', 'formatedEndDate', 'reservations', 'totalPrice', 'totalSeats');
        $pdf = \PDF::loadView('pdf.booker-report', $data);
        return $pdf->download('petar-report.pdf');
        return view('pdf/booker-report', $data);
    }
}
