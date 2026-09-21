<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'title',
        'number',
        'segment',
        'type',
        'duration',
        'is_active',
        'created_at',
        'updated_at',
    ];

     public function answers()
    {
        return $this->hasMany(Answer::class);
    }
     public function roundsetting()
    {
        return $this->hasOne(RoundSetting::class);
    }

    // UBAH HASONE MENJADI HASMANY
    public function roundSettings()
    {
        return $this->hasMany(RoundSetting::class);
    }
}
