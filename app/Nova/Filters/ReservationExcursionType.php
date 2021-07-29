<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

use App\Models\ExcursionType;

class ReservationExcursionType extends Filter
{

    public $name = 'Izlet';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';
    private $excursionTypeId;

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
        $this->excursionTypeId = $value;

        return $query->whereHas('excursion', function (Builder $query) {
            $query->where('excursion_type_id', $this->excursionTypeId);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $excursionTypes = ExcursionType::all();
        $excursionTypesArray = [];
        foreach($excursionTypes as $excursionType){
            $excursionTypesArray[$excursionType->name] = $excursionType->id;
        }

        return $excursionTypesArray;
    }
}
