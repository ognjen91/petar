<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ExcursionType;
use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Station;
use App\Models\PrivateExcursionReservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class DownloadBookerPdfReport extends Controller
{
    /**
     * REGULAR EXCURSIONS REPORT : DETAILED
     */

    public function indexRegularDetailed(Request $request){
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
        $reservations = $reservations->notReturnWay();

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

        $totalPrice = $reservations->active()->get()->sum('price');
        $totalSeats = $reservations->active()->get()->sum('seats');
        
        $reservations = $reservations->active()->get()->sortBy(function($reservation){
            return $reservation->excursion->departure;
        });


        // ======RECAP PART========
        $stationsByTime = [];
        $reservationsByStation = [];
        $sholudShowStationsRecapTable = false;

        if($excursionType && $startDateFromSession == $endDateFromSession){
            $reservationsByStation = self::getReservationsByStation($reservations);
            $sholudShowStationsRecapTable = !!count($reservationsByStation);
        }       
         // ======END RECAP PART========

        // $view = session('booker-pdf-report-type') == 'detailed'? 'pdf.booker-report.regular-detailed' : 'pdf.booker-report.regular-simple';
        // =====PREPARE PDF DATA END=====

        //Clear session data related to booker!
        session()->forget('booker-pdf-report-booker');
        session()->forget('booker-pdf-report-excursion-type');
        session()->forget('booker-pdf-report-start-date');
        session()->forget('booker-pdf-report-end-date');

        // DOWNLOAD PDF
        $data = compact('booker', 'excursionType', 'formatedStartDate', 'formatedEndDate', 'reservations', 'totalPrice', 'totalSeats', 'reservationsByStation', 'sholudShowStationsRecapTable');
        $pdf = \PDF::loadView('pdf.booker-report.regular-detailed', $data);


        return $pdf->download(Carbon::now()->format('d_m_Y-') .' petar-report-redovni-izleti.pdf' );
    }



    /**
     * REGULAR EXCURSIONS REPORT : DETAILED
     */

    public function indexRegularSimple(Request $request){
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
        $reservations = Reservation::active(); //bookers reservations or some default query

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

        $totalPrice = $reservations->active()->get()->sum('price');
        $totalSeats = $reservations->active()->get()->sum('seats');
        
        $reservations = $reservations->active()->get();

        $bookersWhoReserved = User::role('Booker')->whereIn('id', $reservations->pluck('booker_id')->toArray())->get();
        $reservationsByBookers = [];

        foreach($bookersWhoReserved as $bookerWhoReserved){
            $bookersReservations = $bookerWhoReserved->reservations()->whereIn('id', $reservations->pluck('id')->toArray())->get();
            $reservationsByBookers[] =  [
                'booker' => $bookerWhoReserved,
                'reservations' => $bookersReservations
            ];
        }


        // ======RECAP PART========

        $stationsByTime = [];
        $reservationsByStation = [];
        $sholudShowStationsRecapTable = false;
        
        if($excursionType && $startDateFromSession == $endDateFromSession){
            $reservationsByStation = self::getReservationsByStation($reservations);
            $sholudShowStationsRecapTable = !!count($reservationsByStation);
        }
        // ======END RECAP PART========


        // $view = session('booker-pdf-report-type') == 'detailed'? 'pdf.booker-report.regular-detailed' : 'pdf.booker-report.regular-simple';
        // =====PREPARE PDF DATA END=====

        //Clear session data related to booker!
        session()->forget('booker-pdf-report-booker');
        session()->forget('booker-pdf-report-excursion-type');
        session()->forget('booker-pdf-report-start-date');
        session()->forget('booker-pdf-report-end-date');

        // DOWNLOAD PDF
        $data = compact('booker', 'excursionType', 'formatedStartDate', 'formatedEndDate', 'reservations', 'totalPrice', 'totalSeats', 'reservationsByStation', 'sholudShowStationsRecapTable', 'reservationsByBookers', 'bookersWhoReserved');
        $pdf = \PDF::loadView('pdf.booker-report.regular-simple', $data);


        return $pdf->download(Carbon::now()->format('d_m_Y-') .' petar-report-redovni-izleti.pdf' );
    }



    private static function getReservationsByStation($reservations){
        $uniqueExcursionsIdsOfTheReservations = $reservations->unique('excursion_id')->pluck('excursion_id')->toArray();
        $uniqueExcursions = Excursion::whereIn('id', $uniqueExcursionsIdsOfTheReservations)->get();

        foreach($uniqueExcursions as $uniqExcursion){
                $uniqueStationIdsOfTheReservations = $uniqExcursion->reservations->unique('station_id')->pluck('station_id')->toArray();
                $uniqueStations = Station::whereIn('id', $uniqueStationIdsOfTheReservations)->get();

                foreach($uniqueStations as $station){

                    //get reservations with the station
                    $reservationsOfTheStation = $uniqExcursion->reservations()->active()->get()->filter(function($res) use ($station){
                        return $res->station_id == $station->id;
                    });

                    
                    foreach($reservationsOfTheStation as $theReservation){

                        $seats = $reservationsOfTheStation->sum('seats');
                        $childSeats = $reservationsOfTheStation->sum('child_seats');

                        $statData = [
                            'stationName' => $station->name . " : " .Carbon::parse($theReservation->excursion->departure)->format('H:i'),
                            'seats' => $seats,
                            'childSeats' => $childSeats,
                        ];
                        $reservationsByStation[] = $statData;
                    }
                }
            }


            // ONLY UNIQUE STATIONS
            if(count($reservationsByStation)){
                // $sholudShowStationsRecapTable = true;
                
                $unique = array();
                foreach ($reservationsByStation as $value)
                {
                    $unique[$value['stationName']] = $value;
                }
                
                $reservationsByStation = array_values($unique);
            } 

            return $reservationsByStation;
    }




    /**
     * PRIVATE EXCURSIONS REPORT
     */

    public function indexPrivate(Request $request){
        // =====PREPARE PDF DATA START=====

        //get data from session
        $bookerIdFromSession = session('booker-pdf-report-booker');
        $startDateFromSession = session('booker-pdf-report-start-date');
        $endDateFromSession = session('booker-pdf-report-end-date');

        $booker = User::role('Booker')->find($bookerIdFromSession);

        $startDate = Carbon::parse($startDateFromSession)->startOfDay();;
        $endDate = Carbon::parse($endDateFromSession)->endOfDay();;

        $formatedStartDate = $startDate? $startDate->format('d.m.Y.') : null;
        $formatedEndDate = $endDate? $endDate->format('d.m.Y.') : null;

        // FORMAT RESERVATIONS
        $reservations = $booker? $booker->privateExcursionReservations() : PrivateExcursionReservation::whereNotNull('title'); //bookers reservations or some default query


        //take in count start date, if selected
        if($startDate) $reservations = $reservations->where('start', '>=', $startDate);
    
        //take in count end date, if selected
        if($endDate) $reservations = $reservations->where('start', '<=', $endDate);

        $totalPrice = $reservations->active()->get()->sum('price');
        
        $reservations = $reservations->get();



        // =====PREPARE PDF DATA END=====

        //Clear session data related to booker!
        session()->forget('booker-pdf-report-booker');
        session()->forget('booker-pdf-report-start-date');
        session()->forget('booker-pdf-report-end-date');

        // DOWNLOAD PDF

        $data = compact('booker', 'formatedStartDate', 'formatedEndDate', 'reservations', 'totalPrice');
        $pdf = \PDF::loadView('pdf.booker-report.private', $data);
        return $pdf->download(Carbon::now()->format('d_m_Y-') .' petar-report-privatni-izleti.pdf' );
    }
}
