<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;

use App\Nova\Actions\CreateExcursionsOfTypeInDateRange;


class ExcursionType extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ExcursionType::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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
    
     public static function availableForNavigation(Request $request)
    {
        return auth()->user()->isSuperAdmin;
    }

    /**
     * [label in the admin panel]
     * @return [String]
     */

    public static function label()
    {
        return 'Tipovi izleta';
    }

    /**
     * [singular label in the admin panel]
     * @return [String]
     */

    public static function singularLabel()
    {
        return 'Tip izleta';
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
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('Ime izleta', 'name'),
            Select::make('Tip izleta', 'type')->options([
                'regular' => 'Regularni izleti',
                'private' => 'Privatni izleti',
            ]),
            HasMany::make('Aktivni izleti ovog tipa', 'excursions', 'App\Nova\Excursion'),
            BelongsToMany::make('Stanice', 'stations', 'App\Nova\Station')
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
        return [];
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
            new CreateExcursionsOfTypeInDateRange
        ];
    }
}
