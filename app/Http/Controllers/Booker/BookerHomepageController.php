<?php

namespace App\Http\Controllers\Booker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExcursionType;
use App\Models\Excursion;

use App\Http\Resources\ExcursionTypeResource;

class BookerHomepageController extends Controller
{
    
    public function __invoke(){
        $excursionTypes = ExcursionTypeResource::collection(ExcursionType::all())->resolve();
        return view('booker.homepage', compact('excursionTypes'));
    }
}
