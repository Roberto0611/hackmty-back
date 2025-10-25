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
    public function getById($id){
        $place = Place::find($id);
        if(!$place){
            return response()->json(['message' => 'Place not found'], 404);
        }
        return response()->json($place);
    }
}
