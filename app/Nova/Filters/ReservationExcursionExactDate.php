<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Laravel\Nova\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;


class ReservationExcursionExactDate extends DateFilter
{
    public $name = 'Tačan datum';

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
        $theDate = Carbon::parse($value)->startOfDay();
        $tomorrow = Carbon::parse($value)->endOfDay();

        return $query->whereHas('excursion', function (Builder $query) use ($theDate, $tomorrow) {
            $query->whereBetween('departure', [$theDate, $tomorrow]);
        });
    }
}
