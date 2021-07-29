<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

use Illuminate\Database\Eloquent\Builder;

class ReservationExcursionStartDate extends DateFilter
{
    public $name = 'Od datuma';

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
        $value = Carbon::parse($value)->startOfDay();

        return $query->whereHas('excursion', function (Builder $query) use ($value) {
            $query->where('departure', '>=', $value);
        });;
    }
}
