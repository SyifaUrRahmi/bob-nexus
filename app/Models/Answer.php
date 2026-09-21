<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'participant_id',
        'round_id',
        'round_setting_id',
        'answer',
        'attempt',
        'is_correct',
        'score'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
    public function round()
    {
        return $this->belongsTo(Round::class);
    }
     public function roundSetting()
    {
        return $this->belongsTo(RoundSetting::class,'round_setting_id');
    }
}
