<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'queue_number',
        'name',
        'school',
        'status',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
