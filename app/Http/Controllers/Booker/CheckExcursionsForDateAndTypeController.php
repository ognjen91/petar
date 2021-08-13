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
                                ->whereDate('departure', '>', Carbon::now()->toDateTimeString())
                                ->orderBy('departure', 'asc')
                                ->get();


        $excursionType = ExcursionType::find($request->selectedExcursionTypeId);
        $connectedExcursionTypes = $excursionType->connectedExcursionTypes;
        $connectedExcursionTypesIds = $connectedExcursionTypes->pluck('id')->toArray();
        $connectedTypesExist = !!$connectedExcursionTypes->count();
        $theDay = Carbon::parse($request->selectedDate)->endOfDay();
        
        $excursionsOfTheConnectedTypesOnTheDay = [];
        if($excursions->count()){
            $excursionsOfTheConnectedTypesOnTheDay = Excursion::whereHas('excursionType', function (Builder $query) use ($connectedExcursionTypesIds) {
                $query->whereIn('id', $connectedExcursionTypesIds);
            })
            ->whereDate('departure', '=', $theDay->toDateString())
            ->whereDate('departure', '>=', Carbon::now()->toDateTimeString())
            ->orderBy('departure', 'asc')
            ->get();
        }
        
                                
        return response()->json([
            'excursionsOnTheDate' => ExcursionResource::collection($excursions)->resolve(),
            'connectedExcursionsOnTheDate' => ExcursionResource::collection($excursionsOfTheConnectedTypesOnTheDay)->resolve(),
            'connectedTypesExist' => $connectedTypesExist
        ], 201); // Status code here

    }
}
