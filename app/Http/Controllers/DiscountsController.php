<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    public function index(){
        $discounts = Discount::orderBy("created_at","desc")->get();
        return response()->json($discounts);
    }
}
