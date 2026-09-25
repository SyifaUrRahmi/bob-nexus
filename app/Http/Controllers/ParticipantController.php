<?php

namespace App\Http\Controllers;

use App\Models\Participant;

use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::paginate(10);
        return view('dashboard.participants', compact('participants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create_participant');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'queue_number' => 'required|string|max:255|unique:participants,queue_number',
            'name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
        ]);

        $participant = Participant::create([
            'queue_number' => $request->queue_number,
            'name' => $request->name,
            'school' => $request->school,
        ]);

        return redirect("/participants")->with('success', 'Participant added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $participant = Participant::findOrFail($id);
        return view('dashboard.edit_participant', compact('participant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $participant = Participant::findOrFail($id);

        $request->validate([
            'queue_number' => 'required|string|max:255|unique:participants,queue_number,' . $id,
            'name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
        ]);

        $participant->queue_number = $request->queue_number;
        $participant->name = $request->name;
        $participant->school = $request->school;
        $participant->save();

        return redirect('/participants')->with('success', 'Participant updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();
        
        return redirect('/participants')->with('success', 'Participant deleted successfully.');
        
    }

    public function toggleStatus(Participant $participant)
{
    // Jika status saat ini 'active', maka ubah jadi 'eliminated'. Sebaliknya, jadikan 'active'
    $newStatus = $participant->status === 'active' ? 'eliminated' : 'active';

    $participant->update([
        'status' => $newStatus
    ]);

    return redirect()->back()->with('success', 'Status peserta berhasil diubah!');
}
}
