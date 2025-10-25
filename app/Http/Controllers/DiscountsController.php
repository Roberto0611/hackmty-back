<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountsController extends Controller
{
    public function index(){
        $discounts = Discount::orderBy("created_at","desc")->get();
        return response()->json($discounts);
    }
    public function getById($id){
        $discount = Discount::find($id);
        if(!$discount){
            return response()->json(['message' => 'Discount not found'], 404);
        }
        return response()->json($discount);
    }
    public function getByPlace($place_id){
        $discounts = Discount::where('place_id', $place_id)->orderBy("created_at","desc")->get();
        if($discounts->isEmpty()){
            return response()->json(['message' => 'No discounts found for this place'], 404);
        }
        return response()->json($discounts);
    }
    public function getByCategory($category_id){
        $discounts = Discount::where('category_id', $category_id)->orderBy("created_at","desc")->get();
        if($discounts->isEmpty()){
            return response()->json(['message' => 'No discounts found for this category'], 404);
        }
        return response()->json($discounts);
    }

    /**
     * Flattened join version: returns one row per discount schedule matching the day.
     */
    public function getByDay($day){
        $day = intval($day);
        if ($day < 0 || $day > 6) {
            return response()->json(['message' => 'Invalid day value. Use 0-6 (0=Sunday).'], 400);
        }

        $rows = DB::table('discount_schedules')
            ->join('discounts', 'discounts.id', '=', 'discount_schedules.discount_id')
            ->leftJoin('places', 'places.id', '=', 'discounts.place_id')
            ->leftJoin('categories', 'categories.id', '=', 'discounts.category_id')
            ->select(
                'discounts.id as discount_id',
                'discounts.title',
                'discounts.description',
                'discounts.image_url',
                'discounts.place_id',
                'discounts.category_id',
                'discount_schedules.day_of_week',
                'discount_schedules.start_time',
                'discount_schedules.end_time',
                'places.name as place_name',
                'categories.name as category_name',
                'discounts.created_at',
                'discounts.updated_at'
            )
            ->where('discount_schedules.day_of_week', $day)
            ->orderBy('discounts.created_at', 'desc')
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No discounts found for this day'], 404);
        }

        return response()->json($rows);
    }
}
