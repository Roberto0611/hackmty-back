<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = "votes_tables";
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function votable() {
        return $this->morphTo();
    }
}
