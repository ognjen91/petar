<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Carbon\Carbon;

use App\Models\User;
use App\Models\ExcursionType;
use App\Models\Excursion;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;


class CreateBookerPdfReportForRegularExcursions extends Action
{
    use InteractsWithQueue, Queueable;
    public $name = "Kreirajte pdf izvještaj za bukera: redovni izleti";

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {

        // IDEA: ADD DATA TO SESSION AND REDIRECT TO ANOTHER ROUTE TO DOWNLOAD
        session()->flash('booker-pdf-report-booker', $fields->booker_id);
        session()->flash('booker-pdf-report-excursion-type', $fields->excursion_type_id);
        session()->flash('booker-pdf-report-start-date', $fields->start_date);
        session()->flash('booker-pdf-report-end-date', $fields->end_date);


        return Action::redirect(route('booker-pdf-download-regular-excursions'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $fields = [];

        // ===BOOKERS===
        $allBookers = User::role('Booker')->get();
        $bookerOptions = [ 0 => 'Svi Bukeri'];
        foreach($allBookers as $booker){
            $bookerOptions[$booker->id] = $booker->name;
        }
        $fields[] =  Select::make('Buker', 'booker_id')->options($bookerOptions);
        
        // ===BOOKERS===
        $allExcursionTypes = ExcursionType::get();
        $excursionTypeOptions = [ 0 => 'Svi Izleti'];
        foreach($allExcursionTypes as $excurionType){
            $excursionTypeOptions[$excurionType->id] = $excurionType->name;
        }
        $fields[] =  Select::make('Izlet', 'excursion_type_id')->options($excursionTypeOptions);
        
        // START AND END DATE
        $fields[] = Date::make('Početni datum', 'start_date');
        $fields[] = Date::make('Krajnji datum', 'end_date');


        return $fields;
    }
}
