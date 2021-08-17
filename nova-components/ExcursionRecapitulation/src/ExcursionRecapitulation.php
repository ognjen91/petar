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

    public function passToVueComponent($reservations, $stations, $bookers)
    {
        
        return $this->withMeta([
            'reservations' => $reservations,
            'stations' => $stations,
            'bookers' => $bookers
        ]);
    }
}
