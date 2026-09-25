<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\Participant;

use Illuminate\Http\Request;

class ParticipantEntryController extends Controller
{
    public function index()
    {
        $round = Round::where('is_active', true)->first();
        $participants = Participant::where('status', 'active')->get();
        return view('bob.entry.index', compact('round', 'participants'));
    }
}
