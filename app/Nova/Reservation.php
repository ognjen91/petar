<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Boolean;


use App\Nova\Filters\ReservationBooker;
use App\Nova\Filters\ReservationExcursionType;
use App\Nova\Filters\ReservationExcursionExactDate;
use App\Nova\Filters\ReservationExcursionStartDate;
use App\Nova\Filters\ReservationExcursionLastDate;
use App\Nova\Filters\ShowReturnReservations;


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
     * PAGINATION
     */
    public static $perPageOptions = [10, 20, 50, 100];


    /**
     * [label in the admin panel]
     * @return [String]
     */


    public static function label()
    {
        return 'Rezervacije redovnih';
    }

    /**
     * [singular label in the admin panel]
     * @return [String]
     */

    public static function singularLabel()
    {
        return 'Rezervacija redovnih';
    }



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $fields = [
            // ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('Izlet', 'excursion', 'App\Nova\Excursion')->readonly(),
            Text::make('Broj mjesta', 'seats')->readonly(),
            Text::make('Naplaćena cijena', 'price')->readonly(),
            BelongsTo::make('Stanica', 'station', 'App\Nova\Station')->readonly()->sortable(),
            BelongsTo::make('Buker', 'booker', 'App\Nova\User')->readonly()->sortable(),
            Boolean::make('Status', 'active')->readonly(),
            Textarea::make('Dodatna poruka', 'message')->readonly(),
            HasOne::make('Rezervacija povratnog smjera', 'returnDirectionReservation', 'App\Nova\Reservation')->readonly(),
        ];

    
        return $fields;
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
            new ShowReturnReservations
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
