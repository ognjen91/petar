<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ShowReturnReservations extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if($value == 'no-return-way') return $query->whereNull('first_direction_reservation_id');
        if($value == 'only-return-way') return $query->whereNotNull('first_direction_reservation_id');
        return $query;
    }

    /**
     * The default value of the filter.
     *
     * @var string
     */
    public function default()
    {
        return 'no-return-way';
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Bez povratnih rezervacija' => 'no-return-way',
            'Sa povratnim rezervacijama' => 'all',
            'Samo povratne rezervacije' => 'only-return-way'
        ];
    }
}
