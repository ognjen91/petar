<?php

namespace App\Http\Controllers\Booker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateExcursionReservationResource;
use App\Models\PrivateExcursionReservation;
use Carbon\Carbon;
use App\Http\Requests\PrivateTourReservationRequest;



class PrivateExcursionsReservationController extends Controller
{
    public function index(Request $request){
        $futurePrivateExcursions = PrivateExcursionReservation::active()->whereDate('start', '>=', Carbon::today()->toDateString())->get();
        $futurePrivateExcursions = PrivateExcursionReservationResource::collection($futurePrivateExcursions)->resolve();

        return view('booker.private-excursions.index', compact('futurePrivateExcursions'));
    }
    
    
    public function create(){
        return view('booker.private-excursions.create');
    }

    public function store(PrivateTourReservationRequest $request){
        $validatedData = $request->validated();
        $validatedData['booker_id'] = auth()->id();
        $reservation = PrivateExcursionReservation::create($validatedData);

        return response()->json([
            'status' => 'Uspješno rezerivsano'
        ], 201); 
    }
}
