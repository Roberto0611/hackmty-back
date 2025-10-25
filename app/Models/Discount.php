<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function schedules() {
        return $this->hasMany(DiscountSchedules::class);
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'votable');
    }
}
