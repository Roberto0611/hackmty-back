<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
        public function schedules() {
        return $this->hasMany(PlaceSchedule::class);
    }

    public function discounts() {
        return $this->hasMany(Discount::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'places_products')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
