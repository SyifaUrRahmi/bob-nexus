<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundSetting extends Model
{
    protected $fillable = [
        'round_id',
        'session_number',
        'question_number',
        'correct_answer',
        'is_active',
    ];

     public function rounds()
    {
        return $this->belongsTo(Round::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
