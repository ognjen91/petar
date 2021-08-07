<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;

class ExcursionActive extends Filter
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
        $startOfToday = Carbon::now()->startOfDay();
        if($value == 'future') return $query->where('departure', '>=', $startOfToday->toDateTimeString());
        if($value == 'past') return $query->where('departure', '<=', $startOfToday->toDateTimeString());
        return $query;
    }

    /**
     * The default value of the filter.
     *
     * @var string
     */
    public function default()
    {
        return 'future';
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
            'Samo buduće i današnje' => 'future',
            'Samo prošle' => 'past',
            'Svi izleti' => 'all'
        ];
    }
}
