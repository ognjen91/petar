<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use App\Models\ExcursionType;
use App\Models\Reservation;
use App\Models\Station;
use Carbon\Carbon;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Petar\ExcursionFreeSeats\ExcursionFreeSeats;

use App\Nova\Filters\ExcursionType as ExcursionTypeFilter;
use App\Nova\Filters\ExcursionDate;
use App\Nova\Filters\ExcursionStartDate;
use App\Nova\Filters\ExcursionLastDate;
use App\Nova\Filters\ExcursionActive;
use App\Nova\Filters\ExcursionsOrder;
use Petar\ExcursionRecapitulation\ExcursionRecapitulation;
use Illuminate\Database\Eloquent\Builder;


class Excursion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Excursion::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'departure';
    public function title(){
        return $this->excursionType->name . " " . Carbon::parse($this->departure)->format('d.m.Y. H:i');
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

    /**
     * OWERWRITE PARENTS METHOD SO THAT REGULAR USERS CAN SEE THEIR RESTAURANT'S MENU ITEMS ONLY
     */


    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('departure', 'ASC');
    }


    public static function label()
    {
        return 'Izleti';
    }

    /**
     * [singular label in the admin panel]
     * @return [String]
     */

    public static function singularLabel()
    {
        return 'Izlet';
    }    

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $reservations = Reservation::where('excursion_id', $this->id)->get();
        $uniqueStationIdsOfTheReservations = $reservations->unique('station_id')->pluck('station_id');
        $stations = Station::whereIn('id', $uniqueStationIdsOfTheReservations)->get();

        $excursionsType = ExcursionType::all();
        $excursionsTypeArray = [];
        foreach($excursionsType as $type){
            $excursionsTypeArray[$type->id] = $type->name;
        }

        $fields = [
            ExcursionRecapitulation::make('Rekapitulacija')->onlyOnDetail()->passToVueComponent($reservations, $stations),
            ID::make(__('ID'), 'id')->sortable(),
            Number::make('Ukupan broj mjesta', 'total_seats'),
            ExcursionFreeSeats::make('Broj slobodnih mjesta')
            ->passToVueComponent($this->freeSeats)
            ->exceptOnForms(),
            Number::make('Broj mjesta za djecu', 'total_child_seats')->readonly(), //readonly as it shouldnt be set
            DateTime::make('Polazak', 'departure')->sortable(),


            HasMany::make('Rezervacije', 'reservations', 'App\Nova\Reservation'),
            BelongsTo::make('Izlet', 'excursionType', 'App\Nova\ExcursionType'),
            HasMany::make('Istorija promjene broja mjesta', 'seatChangeHistories', 'App\Nova\SeatChangeHistory')->readonly(optional($this->resource)),

        ];

        // $fields[] =  Select::make('Izleta', 'type')->options($excursionsTypeArray);

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
            new ExcursionActive,
            new ExcursionsOrder,
            new ExcursionTypeFilter,
            new ExcursionDate,
            new ExcursionStartDate,
            new ExcursionLastDate,
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
        return [];
    }
}
