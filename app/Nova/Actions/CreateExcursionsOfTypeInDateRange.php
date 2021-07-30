<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Carbon\Carbon;

class CreateExcursionsOfTypeInDateRange extends Action
{
    use InteractsWithQueue, Queueable;
    public $name = 'Kreiraj izlete za opseg datuma';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if($fields->firstDate >= $fields->lastDate){
            return Action::danger('Posljednji datum mora biti nakon prvog datuma!');
            return;
        }

        $actionMessageAdditionalText = "";
        
        $explodedTime = explode(":", $fields->time); //explodes 12:00 to [12,00]
        if(!isset($explodedTime[0]) || !isset($explodedTime[1])) return Action::danger('Molimo da unesete ispravan format vremena');
        // if(!is_int($explodedTime[0]) || !is_int($explodedTime[1])) return Action::danger('Molimo da unesete ispravan format vremena');

        $firstDate = Carbon::parse($fields->firstDate)->set('hour', $explodedTime[0])->set('minute', $explodedTime[1])->setTimezone('Europe/Belgrade');
        $lastDate = Carbon::parse($fields->lastDate)->set('hour', $explodedTime[0])->set('minute', $explodedTime[1])->setTimezone('Europe/Belgrade');
        $activeDate = $firstDate;

        while($activeDate <= $lastDate){
            foreach ($models as $model) {
                $excursionInTheDay = $model->excursions()->where([
                    'departure' => $activeDate->toDateTimeString(),
                ])->first();

                if(!$excursionInTheDay){
                    $model->excursions()->create([
                        'departure' => $activeDate->toDateTimeString(),
                        'total_seats' => $fields->totalSeats
                    ]);
                } else {
                    $actionMessageAdditionalText .=  $activeDate->format('d.m.Y.') . ", ";
                }
            }
            $activeDate->addDay();
        }

        $actionMessage = !$actionMessageAdditionalText? "Izleti su uspješno kreirani." : "Izleti su uspješno kreirani. Ipak, izleti su već postojali za datume: " . $actionMessageAdditionalText;
        return Action::message($actionMessage);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make('Početni datum', 'firstDate'),
            Date::make('Posljednji datum', 'lastDate'),
            Text::make("Vrijeme polaska", 'time'),
            Number::make("Broj mjesta", 'totalSeats')
        ];
    }
}
