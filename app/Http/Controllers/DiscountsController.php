<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    public function getNow(){
        $now = Carbon::now();
        $day = $now->dayOfWeek; // 0 = Sunday ... 6 = Saturday
        $time = $now->format('H:i:s');
        $prevDay = ($day + 6) % 7; // previous day

        // Flattened join: one row per matching schedule with place info.
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
            ->where(function($q) use ($day, $time) {
                // schedules for today where time is within open-close (normal or overnight)
                $q->where('discount_schedules.day_of_week', $day)
                  ->where(function($q2) use ($time) {
                      $q2->where(function($q3) use ($time) {
                          // normal: open_time <= time <= close_time
                          $q3->where('discount_schedules.start_time', '<=', $time)
                             ->where('discount_schedules.end_time', '>=', $time);
                      })
                      ->orWhere(function($q3) use ($time) {
                          // overnight: close_time < open_time and (time >= open_time OR time <= close_time)
                          $q3->whereColumn('discount_schedules.end_time', '<', 'discount_schedules.start_time')
                             ->where(function($q4) use ($time) {
                                  $q4->where('discount_schedules.start_time', '<=', $time)
                                     ->orWhere('discount_schedules.end_time', '>=', $time);
                             });
                      });
                  });
            })
            ->orWhere(function($q) use ($prevDay, $time) {
                // previous day's overnight schedules that extend past midnight into current day
                $q->where('discount_schedules.day_of_week', $prevDay)
                  ->whereColumn('discount_schedules.end_time', '<', 'discount_schedules.start_time')
                  ->where('discount_schedules.end_time', '>=', $time);
            })
            ->orderBy('discounts.title', 'asc')
            ->get();

        if ($rows->isEmpty()) {
            return response()->json(['message' => 'No active discounts right now'], 404);
        }

        return response()->json($rows);
    }

    public function createDiscount(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'place_id' => 'required|integer|exists:places,id',
            'category_id' => 'required|integer|exists:categories,id',
            // validar el archivo enviado (multipart/form-data)
            'image' => 'nullable|file|image|max:5120', // 5 MB
            // optional schedules array
            'schedules' => 'nullable|array',
        ]);

        // If schedules provided, validate each item: day_of_week, start_time, end_time
        $schedules = $request->input('schedules', []);
        if (!empty($schedules)) {
            foreach ($schedules as $index => $sched) {
                $v = Validator::make($sched, [
                    'day_of_week' => 'required|integer|min:0|max:6',
                    'start_time' => 'required|date_format:H:i',
                    'end_time' => 'required|date_format:H:i',
                ]);
                if ($v->fails()) {
                    return response()->json(['message' => 'Invalid schedule at index ' . $index, 'errors' => $v->errors()], 422);
                }
                // additional logical check: end_time after start_time or overnight allowed (we accept both)
            }
        }

        $discounts = new Discount();
        $discounts->title = $request->title;
        $discounts->description = $request->description;
        // si se envía una URL externa la guardamos; si se sube archivo la actualizaremos después
        $discounts->image_url = $request->image_url;
        $discounts->place_id = $request->place_id;
        $discounts->category_id = $request->category_id;
        $discounts->price = $request->price;
        $discounts->save();

    $id = $discounts->id;

        $file = $request->file('image');
        if ($file && $file->isValid()) {
            try {
                // crear nombre único y seguro
                $extension = $file->getClientOriginalExtension() ?: 'jpg';
                $filename = uniqid('discount_') . '.' . $extension;

                // Guardar usando el disco S3 y establecer visibilidad explícita a 'private'
                /** @var \Illuminate\Filesystem\FilesystemAdapter $s3disk */
                $s3disk = Storage::disk('s3');
                $path = $s3disk->putFileAs('discounts/' . $id, $file, $filename, ['visibility' => 'private']);

                // Comprobar que $path es string no vacío antes de usarlo
                if (is_string($path) && strlen(trim($path)) > 0) {
                    // for static analysis: cast disk to FilesystemAdapter which exposes url()
                    /** @var \Illuminate\Filesystem\FilesystemAdapter $s3disk */
                    $s3disk = Storage::disk('s3');
                    $url = $s3disk->url($path);
                    // actualizar la URL de la imagen en la base de datos
                    $discounts->image_url = $url;
                    $discounts->save();
                } else {
                    // registrar para depuración y volver a la respuesta sin URL
                    Log::error("S3 returned empty path when uploading discount image", ['discount_id' => $id, 'original_name' => $file->getClientOriginalName()]);
                }

            } catch (\Exception $e) {
                // registrar el error y devolver respuesta adecuada
                Log::error('Error uploading discount image to S3', ['discount_id' => $id, 'error' => $e->getMessage()]);
                return response()->json(['message' => 'Discount created but image upload failed', 'error' => $e->getMessage(), 'discount' => $discounts], 201);
            }
        }

        // If schedules were provided, insert them into discount_schedules
        if (!empty($schedules)) {
            $rows = [];
            $now = now();
            foreach ($schedules as $sched) {
                $rows[] = [
                    'discount_id' => $id,
                    'day_of_week' => $sched['day_of_week'],
                    'start_time' => $sched['start_time'],
                    'end_time' => $sched['end_time'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            // bulk insert
            DB::table('discount_schedules')->insert($rows);
        }

        return response()->json($discounts->fresh(), 201);
    }

    public function createDiscountSchedule(Request $request){
        $validated = $request->validate([
            'discount_id' => 'required|integer|exists:discounts,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = DB::table('discount_schedules')->insert([
            'discount_id' => $request->discount_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Discount schedule created successfully'], 201);
    }
    
    public function likeDiscount($id){
        // Require authenticated user
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        $userId = auth()->id();
        $votableType = Discount::class;

        // Check existing vote for this user and discount
        $existing = Vote::where('user_id', $userId)
            ->where('votable_id', $id)
            ->where('votable_type', $votableType)
            ->first();

        if ($existing) {
            if ((int) $existing->vote_value === 1) {
                // already liked, do not duplicate
                return response()->json(['message' => 'Already liked'], 200);
            }

            // switch dislike to like
            $existing->vote_value = 1;
            $existing->save();

            return response()->json(['message' => 'Changed vote to like'], 200);
        }

        // create new like
        $vote = new Vote();
        $vote->user_id = $userId;
        $vote->votable_id = $id;
        $vote->votable_type = $votableType;
        $vote->vote_value = 1;
        $vote->save();

        return response()->json(['message' => 'Like registered successfully'], 201);
    }

    public function dislikeDiscount($id){
        // Require authenticated user
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $discount = Discount::find($id);
        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        $userId = auth()->id();
        $votableType = Discount::class;

        // Check existing vote
        $existing = Vote::where('user_id', $userId)
            ->where('votable_id', $id)
            ->where('votable_type', $votableType)
            ->first();

        if ($existing) {
            if ((int) $existing->vote_value === -1) {
                // already disliked
                return response()->json(['message' => 'Already disliked'], 200);
            }

            // switch like to dislike
            $existing->vote_value = -1;
            $existing->save();

            return response()->json(['message' => 'Changed vote to dislike'], 200);
        }

        // create new dislike
        $vote = new Vote();
        $vote->user_id = $userId;
        $vote->votable_id = $id;
        $vote->votable_type = $votableType;
        $vote->vote_value = -1;
        $vote->save();

        return response()->json(['message' => 'Dislike registered successfully'], 201);
    }
}
