<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function getByDay($day){
        $day = intval($day);
        if ($day < 0 || $day > 6) {
            return response()->json(['message' => 'Invalid day value. Use 0-6 (0=Sunday).'], 400);
        }

        $rows = DB::table('place_schedules')
            ->join('places', 'places.id', '=', 'place_schedules.place_id')
            ->select(
                'place_schedules.id as schedule_id',
                'place_schedules.place_id',
                'place_schedules.day_of_week',
                'place_schedules.open_time',
                'place_schedules.close_time',
                'places.name as place_name'
            )
            ->where('place_schedules.day_of_week', $day)
            ->orderBy('place_schedules.open_time', 'asc')
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No places found for this day'], 404);
        }

        return response()->json($rows);
    }

    /**
     * Return places that are open right now according to place_schedules.
     * Handles overnight schedules (close_time < open_time).
     */
    public function getOpenNow(){
        $now = Carbon::now();
        $day = $now->dayOfWeek; // 0 = Sunday ... 6 = Saturday
        $time = $now->format('H:i:s');
        $prevDay = ($day + 6) % 7; // previous day

        // Flattened join: one row per matching schedule with place info.
        $rows = DB::table('place_schedules')
            ->join('places', 'places.id', '=', 'place_schedules.place_id')
            ->select(
                'place_schedules.id as schedule_id',
                'place_schedules.place_id',
                'places.name as place_name',
                'places.latitude',
                'places.longitude',
                'place_schedules.day_of_week',
                'place_schedules.open_time',
                'place_schedules.close_time'
            )
            ->where(function($q) use ($day, $time) {
                // schedules for today where time is within open-close (normal or overnight)
                $q->where('place_schedules.day_of_week', $day)
                  ->where(function($q2) use ($time) {
                      $q2->where(function($q3) use ($time) {
                          // normal: open_time <= time <= close_time
                          $q3->where('place_schedules.open_time', '<=', $time)
                             ->where('place_schedules.close_time', '>=', $time);
                      })
                      ->orWhere(function($q3) use ($time) {
                          // overnight: close_time < open_time and (time >= open_time OR time <= close_time)
                          $q3->whereColumn('place_schedules.close_time', '<', 'place_schedules.open_time')
                             ->where(function($q4) use ($time) {
                                  $q4->where('place_schedules.open_time', '<=', $time)
                                     ->orWhere('place_schedules.close_time', '>=', $time);
                             });
                      });
                  });
            })
            ->orWhere(function($q) use ($prevDay, $time) {
                // previous day's overnight schedules that extend past midnight into current day
                $q->where('place_schedules.day_of_week', $prevDay)
                  ->whereColumn('place_schedules.close_time', '<', 'place_schedules.open_time')
                  ->where('place_schedules.close_time', '>=', $time);
            })
            ->orderBy('places.name', 'asc')
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No places open right now'], 404);
        }

        return response()->json($rows);
    }
}