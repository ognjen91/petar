<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

use App\Nova\Filters\ReservationBooker;
use App\Nova\Filters\ReservationExcursionType;
use App\Nova\Filters\ReservationExcursionExactDate;
use App\Nova\Filters\ReservationExcursionStartDate;
use App\Nova\Filters\ReservationExcursionLastDate;


class Reservation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Reservation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'id';
    public function title(){
        return $this->booker->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * [label in the admin panel]
     * @return [String]
     */

    public static function label()
    {
        return 'Rezervacije';
    }

    /**
     * [singular label in the admin panel]
     * @return [String]
     */

    public static function singularLabel()
    {
        return 'Rezervacija';
    }



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('Izlet', 'excursion', 'App\Nova\Excursion'),
            Text::make('Broj mjesta', 'seats'),
            Text::make('Naplaćena cijena', 'price'),
            BelongsTo::make('Stanica', 'station', 'App\Nova\Station'),
            BelongsTo::make('Buker', 'booker', 'App\Nova\User'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ReservationBooker,
            new ReservationExcursionType,
            new ReservationExcursionExactDate,
            new ReservationExcursionStartDate,
            new ReservationExcursionLastDate,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
        ];
    }
}
