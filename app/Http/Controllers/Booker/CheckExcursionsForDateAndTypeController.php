<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Excursion;
use App\Models\ExcursionType;
use App\Http\Resources\ExcursionResource;
use Illuminate\Database\Eloquent\Builder;

class CheckExcursionsForDateAndTypeController extends Controller
{
    public function __invoke(Request $request){
        $excursions = Excursion::where('excursion_type_id', $request->selectedExcursionTypeId)
                                ->whereDate('departure', '=', Carbon::parse($request->selectedDate)->toDateString())
                                ->get();


        $excursionType = ExcursionType::find($request->selectedExcursionTypeId);
        $connectedExcursionTypes = $excursionType->connectedExcursionTypes;
        $connectedTypesExist = !!$connectedExcursionTypes->count();
        $connectedExcursionTypesIds = $connectedExcursionTypes->pluck('id')->toArray();
        $theDay = Carbon::parse($request->selectedDate)->endOfDay();
        

        $excursionsOfTheConnectedTypesOnTheDay = Excursion::whereHas('excursionType', function (Builder $query) use ($connectedExcursionTypesIds) {
            $query->whereIn('id', $connectedExcursionTypesIds);
        })->whereDate('departure', '=', $theDay->toDateString())->get();

                                
        return response()->json([
            'excursionsOnTheDate' => ExcursionResource::collection($excursions)->resolve(),
            'connectedExcursionsOnTheDate' => ExcursionResource::collection($excursionsOfTheConnectedTypesOnTheDay)->resolve(),
            'connectedTypesExist' => $connectedTypesExist
        ], 201); // Status code here

    }
}
