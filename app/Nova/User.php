<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

use Laravel\Nova\Fields\HasMany;
use App\Nova\Actions\CreateBookerPdfReportForRegularExcursions;
use App\Nova\Actions\CreateBookerPdfReportForPrivateExcursions;
use Laravel\Nova\Fields\BelongsToMany;


class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

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
        'id', 'name', 'email',
    ];

    /**
     * [label in the admin panel]
     * @return [String]
     */

    public static function label()
    {
        return 'Bukeri';
    }

    /**
     * [singular label in the admin panel]
     * @return [String]
     */

    public static function singularLabel()
    {
        return 'Buker';
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
            ID::make()->sortable(),
            
            Gravatar::make()->maxWidth(50),
            
            Text::make('Name')
            ->sortable()
            ->rules('required', 'max:255'),
            
            Text::make('Email')
            ->sortable()
            ->rules('required', 'email', 'max:254')
            ->creationRules('unique:users,email')
            ->updateRules('unique:users,email,{{resourceId}}'),
            
            Password::make('Password')
            ->onlyOnForms()
            ->creationRules('required', 'string', 'min:8')
            ->updateRules('nullable', 'string', 'min:8'),

            

        ];

        $fields[] = HasMany::make('Rezervacije redovnih tura', 'reservations', 'App\Nova\Reservation');
        $fields[] = HasMany::make('Rezervacije privatnih tura', 'privateExcursionReservations', 'App\Nova\PrivateExcursionReservation');
        if(auth()->user()->isSuperAdmin) $fields[] = BelongsToMany::make('Uloga', 'roles', '\App\Nova\Role');


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
            new CreateBookerPdfReportForRegularExcursions,
            new CreateBookerPdfReportForPrivateExcursions
        ];
    }
}
