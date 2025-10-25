<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class placesController extends Controller
{
    public function index(){
        $places = Place::all();
        return response()->json($places);
    }
}
