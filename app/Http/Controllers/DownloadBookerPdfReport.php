<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExcursionType;
use App\Models\Excursion;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class DownloadBookerPdfReport extends Controller
{
    public function __invoke(Request $request){
        // =====PREPARE PDF DATA START=====

        //get data from session
        $bookerIdFromSession = session('booker-pdf-report-booker');
        $excursionTypeIdFromSession = session('booker-pdf-report-excursion-type');
        $startDateFromSession = session('booker-pdf-report-start-date');
        $endDateFromSession = session('booker-pdf-report-end-date');

        $booker = User::role('Booker')->find($bookerIdFromSession);

        $excursionType = ExcursionType::find($excursionTypeIdFromSession);

        $startDate = Carbon::parse($startDateFromSession)->startOfDay();;
        $endDate = Carbon::parse($endDateFromSession)->endOfDay();;

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
        // =====PREPARE PDF DATA END=====

        //Clear session data related to booker!
        session()->forget('booker-pdf-report-booker');
        session()->forget('booker-pdf-report-excursion-type');
        session()->forget('booker-pdf-report-start-date');
        session()->forget('booker-pdf-report-end-date');

        // DOWNLOAD PDF

        $data = compact('booker', 'excursionType', 'formatedStartDate', 'formatedEndDate', 'reservations', 'totalPrice', 'totalSeats');
        $pdf = \PDF::loadView('pdf.booker-report', $data);
        return $pdf->download('petar-report.pdf');
    }
}
