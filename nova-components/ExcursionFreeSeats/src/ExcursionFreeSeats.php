<?php

namespace Petar\ExcursionFreeSeats;

use Laravel\Nova\Fields\Field;

class ExcursionFreeSeats extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'excursion-free-seats';

    public function passToVueComponent($freeSeats)
    {
        return $this->withMeta([
          'freeSeats' =>  $freeSeats,
        ]);
    }
}
