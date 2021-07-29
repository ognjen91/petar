<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Excursion;
use App\Http\Resources\ExcursionResource;

class CheckExcursionsForDateAndTypeController extends Controller
{
    public function __invoke(Request $request){
        // dd(Carbon::parse($request->selectedDate));
        $excursions = Excursion::where('excursion_type_id', $request->selectedExcursionTypeId)
                                ->whereDate('departure', '=', Carbon::parse($request->selectedDate)
                                ->toDateString())
                                ->get();
        return response()->json([
            'excursionsOnTheDate' => ExcursionResource::collection($excursions)->resolve()
        ], 201); // Status code here

    }
}
