<?php

namespace Petar\ExcursionRecapitulation;

use Laravel\Nova\Fields\Field;
use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Station;
use Illuminate\Database\Eloquent\Builder;

class ExcursionRecapitulation extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'excursion-recapitulation';

    public function passToVueComponent($reservations, $stations)
    {
        // $excursion = Excursion::find($id);
        // $reservations =  $excursion->reservations;
        // $reservations = Reservation::where('excursion_id', $excursion->id)->get();
        


        return $this->withMeta([
            'reservations' => $reservations,
            'stations' => $stations
            // 'excursion' => $excursion,
            // 'reservations' => $reservations
            // 'reservations' => $reservations
            // 'reservations' => $excursion->reservations->groupBy(function ($reservation, $key) {
            //                                                     return $reservation->station->name;
            //                                                  })
        ]);
    }
}
