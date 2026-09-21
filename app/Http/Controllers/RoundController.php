<?php

namespace App\Http\Controllers;
use App\Models\Round;
use App\Models\Answer;
use App\Models\RoundSetting;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rounds = Round::all();
        return view('dashboard.rounds', compact('rounds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create_round');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'number' => 'required',
    //         'segment' => 'required',
    //         'title' => 'required',
    //         'type' => 'required|in:numeric,image_sequence,logic,spatial',
    //     ]);

    //     // Validasi khusus Spatial
    //     if ($request->type === 'spatial') {

    //         $request->validate([
    //             'session_count' => 'required|integer|min:1',
    //             'sessions' => 'required|array',
    //         ]);
    //     }

    //     $round = Round::create([
    //         'title' => $request->title,
    //         'type' => $request->type,
    //         'number' => $request->number,
    //         'segment' => $request->segment,
    //     ]);


    //     // ==========================================
    //     // NUMERIC
    //     // ==========================================

    //     if ($request->type === 'numeric') {

    //         $request->validate([
    //             'correct_answer' => 'required',
    //         ]);

    //         RoundSetting::create([
    //             'round_id' => $round->id,
    //             'correct_answer' => $request->correct_answer,
    //             'is_active' => true,
    //         ]);
    //     }


    //     // ==========================================
    //     // IMAGE SEQUENCE
    //     // ==========================================

    //     elseif ($request->type === 'image_sequence') {

    //         $memory = [];

    //         foreach ($request->memory as $row => $columns) {

    //             foreach ($columns as $col => $image) {

    //                 if ($image) {

    //                     $path = $image->store('memory', 'public');

    //                     $memory[$row][$col] = $path;
    //                 }
    //             }
    //         }

    //         RoundSetting::create([
    //             'round_id' => $round->id,
    //             'correct_answer' => json_encode($memory),
    //             'is_active' => true,
    //         ]);
    //     }


    //     // ==========================================
    //     // SPATIAL
    //     // ==========================================

    //     elseif ($request->type === 'spatial') {

    //         foreach ($request->sessions as $sessionNumber => $questions) {

    //             foreach ($questions as $questionNumber => $correctAnswer) {

    //                 RoundSetting::create([
    //                     'round_id' => $round->id,
    //                     'session_number' => $sessionNumber,
    //                     'question_number' => $questionNumber,
    //                     'correct_answer' => $correctAnswer,
    //                     'is_active' => false,
    //                 ]);
    //             }
    //         }

    //         // Aktifkan Sesi 1 - Soal 1
    //         RoundSetting::where('round_id', $round->id)
    //             ->where('session_number', 1)
    //             ->where('question_number', 1)
    //             ->update([
    //                 'is_active' => true,
    //             ]);
    //     }

    //     // ==========================================
    //     // LOGICAL (Segment 4 - Cognitive Logic Test)
    //     // ==========================================
    //     elseif ($request->type === 'logic') {

    //         $request->validate([
    //             'logic_answers' => 'required|array',
    //         ]);

    //         foreach ($request->logic_answers as $questionNumber => $correctAnswer) {
    //             RoundSetting::create([
    //                 'round_id'        => $round->id,
    //                 'session_number'  => 1,
    //                 'question_number' => $questionNumber,
    //                 'correct_answer'  => strtoupper(trim($correctAnswer)),
    //                 'is_active'       => true,
    //             ]);
    //         }
    //     }


    //     return redirect('/rounds')
    //         ->with('success', 'Round added successfully');
    // }

    public function store(Request $request)
    {
        // 1. Validasi Input Utama
        $request->validate([
            'number'  => 'required',
            'segment' => 'required',
            'title'   => 'required',
            'type'    => 'required|in:numeric,image_sequence,logic,spatial,string',
        ]);

        // Validasi spesifik per tipe
        if ($request->type === 'spatial') {
            $request->validate([
                'correct_answers' => 'required|array',
            ]);
        } elseif ($request->type === 'logic') {
            $request->validate([
                'logic_answers' => 'required|array',
            ]);
        } elseif ($request->type === 'numeric') {
            $request->validate([
                'correct_answer' => 'required',
            ]);
        } elseif ($request->type === 'string') {
            $request->validate([
                'correct_answer' => 'required|string',
            ]);
        }
        

        // 2. Buat Master Round
        $round = Round::create([
            'title'   => $request->title,
            'type'    => $request->type,
            'number'  => $request->number,
            'segment' => $request->segment,
        ]);

        // ==========================================
        // 1. NUMERIC
        // ==========================================
        if ($request->type === 'numeric') {
            RoundSetting::create([
                'round_id'       => $round->id,
                'correct_answer' => $request->correct_answer,
                'is_active'      => true,
            ]);
        }

        // ==========================================
        // 2. IMAGE SEQUENCE
        // ==========================================
        elseif ($request->type === 'image_sequence') {
            $memory = [];

            foreach ($request->memory as $row => $columns) {
                foreach ($columns as $col => $image) {
                    if ($image) {
                        $path = $image->store('memory', 'public');
                        $memory[$row][$col] = $path;
                    }
                }
            }

            RoundSetting::create([
                'round_id'       => $round->id,
                'correct_answer' => json_encode($memory),
                'is_active'      => true,
            ]);
        }

        // ==========================================
        // 3. SPATIAL (12 Soal tanpa Sesi)
        // ==========================================
        elseif ($request->type === 'spatial') {
            foreach ($request->correct_answers as $questionNumber => $correctAnswer) {
                RoundSetting::create([
                    'round_id'        => $round->id,
                    'session_number'  => 1, // Default ke 1 karena kolom DB mensyaratkan/opsional
                    'question_number' => $questionNumber,
                    'correct_answer'  => $correctAnswer,
                    'is_active'       => true,
                ]);
            }
        }

        // ==========================================
        // 4. LOGICAL
        // ==========================================
        elseif ($request->type === 'logic') {
            foreach ($request->logic_answers as $questionNumber => $correctAnswer) {
                RoundSetting::create([
                    'round_id'        => $round->id,
                    'session_number'  => 1,
                    'question_number' => $questionNumber,
                    'correct_answer'  => strtoupper(trim($correctAnswer)),
                    'is_active'       => true,
                ]);
            }
        }

        // ==========================================
        // 5. STRING
        // ==========================================
        if ($request->type === 'string') {
            RoundSetting::create([
                'round_id'       => $round->id,
                'correct_answer' => $request->correct_answer,
                'is_active'      => true,
            ]);
        }

        return redirect('/rounds')->with('success', 'Round added successfully');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {   
    //     $round = Round::findOrFail($id);
    //     $activeSetting = $round->roundsettings()
    //         ->where('is_active', true)
    //         ->first();
    //     $answersQuery = $round->answers()->with('participant');
    //     if ($round->type === 'spatial' && $activeSetting) {
    //         $answersQuery->where('round_setting_id', $activeSetting->id);
    //     }

    //     $answers = $answersQuery->with('roundSetting')
    //         ->orderByDesc('is_correct') // Jawaban benar di atas
    //         ->orderBy('created_at')      // Jawaban tercepat di atas
    //         ->get(); // Gunakan ->get() agar bisa di-compact ke view jika tanpa pagination

    //         // dd($answers);
    //     $answers->each(function ($answer) {
    //         $answer->setAttribute(
    //             'question_number',
    //             $answer->roundSetting?->question_number
    //         );
    //     });
    //     $settings = $round->roundsettings()
    //         ->orderBy('session_number')
    //         ->orderBy('question_number')
    //         ->get();

    //     return view('dashboard.round_details', compact('round', 'answers', 'settings', 'activeSetting'));
    // }
    public function show(string $id)
    {   
        $round = Round::findOrFail($id);

        // Ambil setting yang sedang aktif
        $activeSetting = $round->roundsettings()
            ->where('is_active', true)
            ->first();

        // Query Jawaban
        $answersQuery = $round->answers()->with('participant');

        // KHUSUS SPATIAL: Filter jawaban berdasarkan round_setting yang sedang aktif
        if ($round->type === 'spatial' && $activeSetting) {
            $answersQuery->where('round_setting_id', $activeSetting->id);
        }

        $answers = $answersQuery->with('roundSetting')
            ->orderByDesc('is_correct') // Jawaban benar di atas
            ->orderBy('created_at', 'desc')      // Jawaban tercepat di atas
            ->get();

        $answers->each(function ($answer) {
            $answer->setAttribute(
                'question_number',
                $answer->roundSetting?->question_number
            );
        });

        // HAPUS orderBy('session_number'), CUKUP ORDER BERDASARKAN question_number
        $settings = $round->roundsettings()
            ->orderBy('question_number')
            ->get();

        return view('dashboard.round_details', compact('round', 'answers', 'settings', 'activeSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $round = Round::with('roundSettings')->findOrFail($id);

        // Ambil Kunci Jawaban Spatial
        $spatialAnswers = [];
        if ($round->type === 'spatial') {
            $spatialAnswers = $round->roundSettings
                ->pluck('correct_answer', 'question_number')
                ->toArray();
        }

        // Ambil Kunci Jawaban Logic
        $logicAnswers = [];
        if ($round->type === 'logic' || $round->type === 'logical') {
            $logicAnswers = $round->roundSettings
                ->pluck('correct_answer', 'question_number')
                ->toArray();
        }

        return view('dashboard.edit_round', compact('round', 'spatialAnswers', 'logicAnswers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $round = Round::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'number'  => 'required|integer',
            'title'   => 'required',
            'segment' => 'required|integer',
            'type'    => 'required|in:numeric,image_sequence,logic,spatial,string',
        ]);

        // 2. Update data rounds
        $round->number  = $request->number;
        $round->segment = $request->segment;
        $round->title   = $request->title;
        $round->type    = $request->type;
        $round->save();

        // 3. Ambil relasi roundsetting / roundSetting
        $setting = $round->roundsetting;
        if (!$setting) {
            $setting = $round->roundsetting()->create([]);
        }

        $type = $request->type;
        // 4. Proses Simpan Khusus image_sequence
        if ($type === 'image_sequence') {            
            // 1. Ambil data memory lama dari correct_answer
            $currentMemory = [];
            if (!empty($setting->correct_answer)) {
                $raw = $setting->correct_answer;
                $currentMemory = is_string($raw) ? json_decode($raw, true) : $raw;
            }

            // 2. Ambil array file upload langsung via $request->file('memory')
            $memoryFiles = $request->file('memory');

            if ($memoryFiles && is_array($memoryFiles)) {
                // dd("tes");
                foreach ($memoryFiles as $row => $cols) {
                    if (is_array($cols)) {
                        foreach ($cols as $col => $file) {
                            // Pastikan file benar-benar diunggah dan valid
                            if ($file && $file->isValid()) {
                                
                                // Hapus gambar lama jika ada pengganti
                                if (isset($currentMemory[$row][$col]) && \Storage::disk('public')->exists($currentMemory[$row][$col])) {
                                    \Storage::disk('public')->delete($currentMemory[$row][$col]);
                                }

                                // Upload file baru ke folder public/memory
                                $path = $file->store('memory', 'public');
                                
                                // Buat struktur baris jika belum ada
                                if (!isset($currentMemory[$row])) {
                                    $currentMemory[$row] = [];
                                }
                                
                                // Simpan path ke array
                                $currentMemory[$row][$col] = $path;
                            }
                        }
                    }
                }
            }

            // 3. Simpan kembali ke kolom correct_answer dalam format JSON String
            $setting->correct_answer = json_encode($currentMemory);
            $setting->save();
        }
        elseif ($request->type === 'spatial') {
            // Simpan/Update per nomor soal
            foreach ($request->correct_answers as $questionNumber => $correctAnswer) {
                RoundSetting::updateOrCreate(
                    [
                        'round_id'        => $round->id,
                        'question_number' => $questionNumber,
                    ],
                    [
                        'session_number'  => 1,
                        'correct_answer'  => $correctAnswer,
                        'is_active'       => true,
                    ]
                );
            }
        }
        elseif ($request->type === 'logic') {
            foreach ($request->logic_answers as $questionNumber => $correctAnswer) {
                RoundSetting::updateOrCreate(
                    [
                        'round_id'        => $round->id,
                        'question_number' => $questionNumber,
                    ],
                    [
                        'session_number' => 1,
                        'correct_answer' => strtoupper(trim($correctAnswer)),
                        'is_active'      => true,
                    ]
                );
            }
        }

        else {
            $setting->correct_answer = $request->correct_answer;
            $setting->save();
        }

        return redirect('/rounds')->with('success', 'Round updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $round = Round::findOrFail($id);
        $roundSetting = RoundSetting::where('round_id', $round->id)->first();

        if ($roundSetting && $round->type === 'image_sequence') {
            $memory = json_decode($roundSetting->correct_answer, true);

            if (is_array($memory)) {
                foreach ($memory as $rowNum => $row) {
                    if (is_array($row)) {
                        foreach ($row as $key => $imagePath) {
                            if (!empty($imagePath)) {
                                $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $imagePath), '/');
                                Storage::disk('public')->delete($cleanPath);
                            }
                        }
                    }
                }
            }
        }
        if ($roundSetting) {
            $roundSetting->delete();
        }
        $round->delete();

        return redirect('/rounds')
            ->with('success', 'Round deleted successfully.');
    }
    // public function spatialControl(Request $request, $id)
    // {
    //     // Ambil nomor soal yang diklik dari request
    //     $targetQuestion = $request->input('question_number');

    //     if (!$targetQuestion) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Nomor soal tidak valid.'
    //         ], 400);
    //     }

    //     // 1. Nonaktifkan SEMUA soal di ronde ini
    //     RoundSetting::where('round_id', $id)->update([
    //         'is_active' => false
    //     ]);

    //     // 2. Aktifkan HANYA soal sesuai nomor yang diklik
    //     $updated = RoundSetting::where('round_id', $id)
    //         ->where('question_number', $targetQuestion)
    //         ->update([
    //             'is_active' => true
    //         ]);

    //     if ($updated) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Soal ' . $targetQuestion . ' berhasil diaktifkan.'
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Soal nomor ' . $targetQuestion . ' tidak ditemukan di database.'
    //     ], 444);
    // }
    public function spatialControl(Request $request, $id)
    {
        // 1. Ambil nomor soal yang diklik admin dari JS (1-12)
        $targetQuestion = $request->input('question_number');

        if (!$targetQuestion) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor soal tidak valid.'
            ], 400);
        }

        // 2. Nonaktifkan SEMUA soal di ronde ini
        RoundSetting::where('round_id', $id)->update([
            'is_active' => false
        ]);

        // 3. Aktifkan HANYA soal sesuai nomor yang diklik
        $updated = RoundSetting::where('round_id', $id)
            ->where('question_number', $targetQuestion)
            ->update([
                'is_active' => true
            ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Soal ' . $targetQuestion . ' berhasil diaktifkan.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Soal nomor ' . $targetQuestion . ' tidak ditemukan di database.'
        ], 404);
    }

    public function getAnswers(Request $request, $id)
    {
        $round = Round::findOrFail($id);
        
        $query = Answer::with('participant', 'roundSetting')
            ->where('round_id', $id);

        // KHUSUS SPATIAL: Menyaring berdasarkan round_setting_id yang aktif
        if ($round->type === 'spatial') {
            if ($request->has('round_setting_id') && $request->round_setting_id > 0) {
                $query->where('round_setting_id', $request->round_setting_id);
            }
        } 
        // TIPE LAIN: Filter berdasarkan question_number jika ada
        elseif ($request->has('question_number') && $request->question_number > 0) {
            $query->where('question_number', $request->question_number);
        }

        $answers = $query->orderByDesc('is_correct')
            ->orderBy('created_at')
            ->get();

        return response()->json($answers);
    }

    public function activate($id)
    {
        Round::query()->update(['is_active' => false]);

        // aktifkan round yang dipilih
        $round = Round::findOrFail($id);
        $round->is_active = true;
        $round->save();

        return redirect()->back()->with('success', 'Round activated successfully');
    }

    // public function nextSpatial($roundId)
    // {
    //     $round = Round::findOrFail($roundId);

    //     $current = $round->roundsettings()
    //         ->where('is_active', true)
    //         ->first();

    //     if (!$current) {
    //         return back()->with('error', 'Tidak ada soal aktif.');
    //     }

    //     $next = $round->roundsettings()
    //         ->where(function ($query) use ($current) {

    //             $query->where('session_number', '>', $current->session_number)

    //                 ->orWhere(function ($query) use ($current) {

    //                     $query->where(
    //                         'session_number',
    //                         $current->session_number
    //                     )
    //                     ->where(
    //                         'question_number',
    //                         '>',
    //                         $current->question_number
    //                     );

    //                 });

    //         })
    //         ->orderBy('session_number')
    //         ->orderBy('question_number')
    //         ->first();

    //     if (!$next) {
    //         return back()->with(
    //             'success',
    //             'Semua soal Spatial telah selesai.'
    //         );
    //     }

    //     $round->roundsettings()
    //         ->update([
    //             'is_active' => false
    //         ]);

    //     $next->update([
    //         'is_active' => true
    //     ]);

    //     return back()->with(
    //         'success',
    //         'Soal berikutnya telah diaktifkan.'
    //     );
    // }

    public function nextSpatial($roundId)
    {
        $round = Round::findOrFail($roundId);

        $current = $round->roundsettings()
            ->where('is_active', true)
            ->first();

        if (!$current) {
            return back()->with('error', 'Tidak ada soal aktif.');
        }

        // Cari soal berikutnya murni berdasarkan question_number
        $next = $round->roundsettings()
            ->where('question_number', '>', $current->question_number)
            ->orderBy('question_number')
            ->first();

        if (!$next) {
            return back()->with('success', 'Semua soal Spatial telah selesai.');
        }

        // Switch status aktif ke soal berikutnya
        $round->roundsettings()->update(['is_active' => false]);
        $next->update(['is_active' => true]);

        return back()->with('success', 'Soal berikutnya telah diaktifkan.');
    }

    // public function controlSpatial(Request $request, $id)
    // {
    //     $action = $request->action;

    //     if ($action === 'start') {
    //         // Nonaktifkan semua soal pada round ini lebih dulu
    //         RoundSetting::where('round_id', $id)->update(['is_active' => false]);

    //         // Aktifkan Sesi 1 - Soal 1
    //         RoundSetting::where('round_id', $id)
    //             ->where('session_number', 1)
    //             ->where('question_number', 1)
    //             ->update(['is_active' => true]);

    //         return response()->json(['success' => true]);
    //     }

    //     // Ambil soal yang sedang aktif saat ini
    //     $currentActive = RoundSetting::where('round_id', $id)
    //         ->where('is_active', true)
    //         ->first();

    //     if (!$currentActive) {
    //         return response()->json([
    //             'success' => false, 
    //             'message' => 'Belum ada soal yang aktif. Klik MULAI terlebih dahulu.'
    //         ]);
    //     }

    //     // Cari soal berikutnya atau sebelumnya berdasarkan ID / nomor urut
    //     if ($action === 'next') {
    //         $targetSetting = RoundSetting::where('round_id', $id)
    //             ->where('id', '>', $currentActive->id)
    //             ->orderBy('id', 'asc')
    //             ->first();
    //     } elseif ($action === 'prev') {
    //         $targetSetting = RoundSetting::where('round_id', $id)
    //             ->where('id', '<', $currentActive->id)
    //             ->orderBy('id', 'desc')
    //             ->first();
    //     }

    //     // Jika soal target ditemukan, lakukan switch is_active di DB
    //     if (isset($targetSetting) && $targetSetting) {
    //         // 1. Nonaktifkan soal yang sekarang
    //         $currentActive->update(['is_active' => false]);

    //         // 2. Aktifkan soal target
    //         $targetSetting->update(['is_active' => true]);

    //         return response()->json(['success' => true]);
    //     }

    //     return response()->json([
    //         'success' => false, 
    //         'message' => $action === 'next' ? 'Sudah mencapai soal terakhir.' : 'Sudah di soal pertama.'
    //     ]);
    // }
    public function controlSpatial(Request $request, $id)
    {
        $action = $request->action;

        if ($action === 'start') {
            RoundSetting::where('round_id', $id)->update(['is_active' => false]);

            // Aktifkan langsung Soal 1
            RoundSetting::where('round_id', $id)
                ->where('question_number', 1)
                ->update(['is_active' => true]);

            return response()->json(['success' => true]);
        }

        $currentActive = RoundSetting::where('round_id', $id)
            ->where('is_active', true)
            ->first();

        if (!$currentActive) {
            return response()->json([
                'success' => false, 
                'message' => 'Belum ada soal yang aktif. Klik MULAI terlebih dahulu.'
            ]);
        }

        if ($action === 'next') {
            $targetSetting = RoundSetting::where('round_id', $id)
                ->where('question_number', '>', $currentActive->question_number)
                ->orderBy('question_number', 'asc')
                ->first();
        } elseif ($action === 'prev') {
            $targetSetting = RoundSetting::where('round_id', $id)
                ->where('question_number', '<', $currentActive->question_number)
                ->orderBy('question_number', 'desc')
                ->first();
        }

        if (isset($targetSetting) && $targetSetting) {
            $currentActive->update(['is_active' => false]);
            $targetSetting->update(['is_active' => true]);

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false, 
            'message' => $action === 'next' ? 'Sudah mencapai soal terakhir.' : 'Sudah di soal pertama.'
        ]);
    }

    // public function getActiveQuestion($roundId)
    // {
    //     $active = RoundSetting::where('round_id', $roundId)
    //         ->where('is_active', true)
    //         ->first();

    //     return response()->json([
    //         'round_setting_id' => $active ? $active->id : null,
    //         'session_number'   => $active ? $active->session_number : 0,
    //         'question_number'  => $active ? $active->question_number : 0,
    //     ]);
    // }

    public function getActiveQuestion($roundId)
    {
        $active = RoundSetting::where('round_id', $roundId)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'round_setting_id' => $active ? $active->id : null,
            'question_number'  => $active ? $active->question_number : 0,
        ]);
    }

    // public function submitSpatialAnswer(Request $request)
    // {
    //     // Validasi input sesuai kolom di tabel answers
    //     $request->validate([
    //         'participant_id'   => 'required|exists:participants,id',
    //         'round_id'         => 'required|exists:rounds,id',
    //         'round_setting_id' => 'required|exists:round_settings,id',
    //         'answer'           => 'required',
    //     ]);

    //     // 1. Ambil data setting soal berdasarkan round_setting_id
    //     $setting = RoundSetting::findOrFail($request->round_setting_id);

    //     // 2. Cek apakah jawaban siswa benar
    //     $isCorrect = (trim($setting->correct_answer) === trim($request->answer));

    //     // 3. Hitung Poin berdasarkan aturan nomor soal (Soal 1-4: 10, Soal 5-9: 20, Soal 10-12: 40)
    //     $score = 0;
    //     if ($isCorrect) {
    //         $qNum = (int) $setting->question_number;
    //         if ($qNum <= 4) {
    //             $score = 10;
    //         } elseif ($qNum <= 9) {
    //             $score = 20;
    //         } else {
    //             $score = 40;
    //         }
    //     }

    //     // 4. Simpan ke database (Sesuai kolom migrasi answers Anda)
    //     $answer = Answer::create([
    //         'participant_id'   => $request->participant_id,
    //         'round_id'         => $request->round_id,
    //         'round_setting_id' => $request->round_setting_id,
    //         'answer'           => $request->answer,
    //         'attempt'          => 1,
    //         'is_correct'       => $isCorrect,
    //         'score'            => $score,
    //     ]);

    //     return response()->json([
    //         'status'     => 'success',
    //         'is_correct' => $isCorrect,
    //         'score'      => $score,
    //         'data'       => $answer
    //     ]);
    // }

    public function submitSpatialAnswer(Request $request)
    {
        $request->validate([
            'participant_id'   => 'required|exists:participants,id',
            'round_id'         => 'required|exists:rounds,id',
            'round_setting_id' => 'required|exists:round_settings,id',
            'answer'           => 'required',
        ]);

        // 1. Ambil setting soal berdasarkan ID aktif saat ini
        $setting = RoundSetting::findOrFail($request->round_setting_id);

        // 2. Cek kebenaran jawaban
        $isCorrect = (trim($setting->correct_answer) === trim($request->answer));

        // 3. Hitung Poin berdasarkan nomor soal (1-4: 10, 5-9: 20, 10-12: 40)
        $score = 0;
        if ($isCorrect) {
            $qNum = (int) $setting->question_number;
            if ($qNum <= 4) {
                $score = 10;
            } elseif ($qNum <= 9) {
                $score = 20;
            } else {
                $score = 40;
            }
        }

        // 4. Simpan atau perbarui jawaban peserta (Prevent Duplicate Submission)
        $answer = Answer::updateOrCreate(
            [
                'participant_id'   => $request->participant_id,
                'round_id'         => $request->round_id,
                'round_setting_id' => $request->round_setting_id,
            ],
            [
                'answer'     => $request->answer,
                'attempt'    => 1,
                'is_correct' => $isCorrect,
                'score'      => $score,
            ]
        );

        return response()->json([
            'status'     => 'success',
            'is_correct' => $isCorrect,
            'score'      => $score,
            'data'       => $answer
        ]);
    }

    public function logicalStatuses(Request $request, Round $round)
{
    $participantId = $request->participant_id;

    // Soal yang sudah dijawab BENAR oleh siapa pun
    $globalCorrectQuestions = Answer::join('round_settings', 'answers.round_setting_id', '=', 'round_settings.id')
        ->where('answers.round_id', $round->id)
        ->where('answers.is_correct', true)
        ->pluck('round_settings.question_number')
        ->unique()
        ->values();

    // Soal yang dijawab SALAH oleh peserta ini
    $selfWrongQuestions = Answer::join('round_settings', 'answers.round_setting_id', '=', 'round_settings.id')
        ->where('answers.round_id', $round->id)
        ->where('answers.participant_id', $participantId)
        ->where('answers.is_correct', false)
        ->pluck('round_settings.question_number')
        ->unique()
        ->values();

    return response()->json([
        'global_correct_questions' => $globalCorrectQuestions,
        'self_wrong_questions'     => $selfWrongQuestions,
    ]);
}
}

