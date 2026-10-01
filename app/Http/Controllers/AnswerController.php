<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\RoundSetting;
use App\Models\Answer;
use App\Models\Participant;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
// public function show($id, $roundId)
// {
//     $participant = Participant::findOrFail($id);
//     $round = Round::findOrFail($roundId);

//     $roundSetting = RoundSetting::where('round_id', $round->id)
//         ->where('is_active', true)
//         ->orderBy('session_number')
//         ->orderBy('question_number')
//         ->first();

//     $images = [];

//     if ($round->type == 'image_sequence') {

//         $correctAnswer = json_decode(
//             $roundSetting->correct_answer,
//             true
//         );

//         foreach ($correctAnswer as $row) {
//             foreach ($row as $path) {
//                 $images[] = $path;
//             }
//         }

//         shuffle($images);
//     }

//     return view('bob.answers.index', compact(
//         'participant',
//         'round',
//         'roundSetting',
//         'images'
//     ));
// }

public function show($id, $roundId)
{
    $participant = Participant::findOrFail($id);

    // 1. Muat $round beserta relasi roundSettings yang SUDAH DIURUTKAN berdasarkan question_number
    $round = Round::with(['roundsettings' => function ($query) {
        $query->orderBy('question_number', 'asc');
    }])->findOrFail($roundId);

    // 2. Ambil setting aktif (Digunakan untuk tipe selain Logical)
    $roundSetting = RoundSetting::where('round_id', $round->id)
        ->where('is_active', true)
        ->orderBy('session_number')
        ->orderBy('question_number')
        ->first();

    $images = [];

    if ($round->type == 'image_sequence' && $roundSetting) {
        $correctAnswer = json_decode(
            $roundSetting->correct_answer,
            true
        );

        if (is_array($correctAnswer)) {
            foreach ($correctAnswer as $row) {
                foreach ($row as $path) {
                    $images[] = $path;
                }
            }
            shuffle($images);
        }
    }

    return view('bob.answers.index', compact(
        'participant',
        'round',
        'roundSetting',
        'images'
    ));
}
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'participant_id' => 'required|exists:participants,id',
    //         'round_id' => 'required|exists:rounds,id',
    //         'answer' => 'required',
    //     ]);

    //     $round = Round::findOrFail($request->round_id);

    //     // Cek jumlah percobaan
    //     $attempt = Answer::where('participant_id', $request->participant_id)
    //         ->where('round_id', $request->round_id)
    //         ->count() + 1;

    //     if ($attempt > 3) {
    //         return back()->with('error', 'Kesempatan menjawab sudah habis.');
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | IMAGE SEQUENCE
    //     |--------------------------------------------------------------------------
    //     */
    //     if ($round->type === 'logic') {
    //         try {
    //             $request->validate([
    //                 'question_number'  => 'required',
    //                 'round_setting_id' => 'required|exists:round_settings,id',
    //             ]);

    //             // Cek apakah peserta sudah pernah menjawab nomor soal ini
    //             $alreadyAnswered = Answer::where('participant_id', $request->participant_id)
    //                 ->where('round_id', $request->round_id)
    //                 ->where('round_setting_id', $request->round_setting_id)
    //                 ->exists();

    //             if ($alreadyAnswered) {
    //                 return response()->json([
    //                     'status'  => 'error',
    //                     'message' => 'Anda sudah pernah menjawab nomor soal ini.'
    //                 ], 422);
    //             }

    //             // Ambil kunci jawaban dari round_setting_id
    //             $setting = RoundSetting::find($request->round_setting_id);
                
    //             $userAnswer    = strtoupper(trim($request->answer));
    //             $correctAnswer = $setting ? strtoupper(trim($setting->correct_answer)) : '';

    //             $isCorrect = ($userAnswer === $correctAnswer);
    //             $score     = $isCorrect ? 1 : 0;

    //             // Simpan ke database
    //             $answer = Answer::create([
    //                 'participant_id'   => $request->participant_id,
    //                 'round_id'         => $request->round_id,
    //                 'round_setting_id' => $setting ? $setting->id : null,
    //                 'answer'           => $request->answer,
    //                 'attempt'          => 1,
    //                 'is_correct'       => $isCorrect,
    //                 'score'            => $score,
    //             ]);

    //             return response()->json([
    //                 'status'     => 'success',
    //                 'is_correct' => $isCorrect,
    //                 'answer'     => $answer
    //             ]);

    //         } catch (\Exception $e) {
    //             // Tangkap error PHP/DB dan kembalikan sebagai JSON agar JS tidak crash
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => $e->getMessage()
    //             ], 500);
    //         }
    //     } 
    //     elseif ($round->type === 'image_sequence') 
    //     {

    //         // Jawaban peserta
    //         $userAnswer = $request->answer;

    //         // Jawaban benar
    //         $correctAnswer = json_decode(
    //             $round->roundsetting->correct_answer,
    //             true
    //         );

    //     //     dd([
    //     //     'user_answer' => $userAnswer,
    //     //     'correct_answer' => $correctAnswer,
    //     // ]);

    //         // Hitung jumlah gambar yang benar
    //         $correctCount = 0;

    //         // Hitung total gambar
    //         $totalImage = 0;

    //         foreach ($correctAnswer as $row => $columns) {

    //             foreach ($columns as $col => $correctImage) {

    //                 $totalImage++;

    //                 $userImage = $userAnswer[$row][$col] ?? null;

    //                 if ($userImage === $correctImage) {
    //                     $correctCount++;
    //                 }
    //             }
    //         }

    //         // Semua gambar benar
    //         $isCorrect = $correctCount === $totalImage;

    //         // Score = jumlah gambar benar
    //         $score = $correctCount;

    //     }
    //     else {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | NUMERIC / LOGICAL
    //         |--------------------------------------------------------------------------
    //         */

    //         $isCorrect =
    //             trim($request->answer) ==
    //             trim($round->roundsetting->correct_answer);
                

    //         $score = $isCorrect ? 1 : 0;
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | SIMPAN JAWABAN
    //     |--------------------------------------------------------------------------
    //     */

    
    //     $roundSettingId = $round->roundSettings->first()?->id;
    //     //  dd($roundSettingId);
    //     $answer = Answer::create([
    //         'participant_id' => $request->participant_id,
    //         'round_id' => $request->round_id,
    //         'round_setting_id' => $roundSettingId,
    //         'answer' => is_array($request->answer)
    //             ? json_encode($request->answer)
    //             : $request->answer,

    //         'attempt' => $attempt,

    //         'is_correct' => $isCorrect,

    //         'score' => $score,
    //     ]);

    //     return redirect()->route('answer.result', $answer->id);
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'participant_id' => 'required|exists:participants,id',
    //         'round_id'       => 'required|exists:rounds,id',
    //         'answer'         => 'required',
    //     ]);

    //     $round = Round::findOrFail($request->round_id);

    //     // Cek jumlah percobaan yang sudah pernah dilakukan
    //     $existingAttempts = Answer::where('participant_id', $request->participant_id)
    //         ->where('round_id', $request->round_id)
    //         ->count();

    //     // Hitung attempt ke berapa untuk transaksi saat ini
    //     $attempt = $existingAttempts + 1;

    //     // Kunci jika sudah mencapai 3 kali percobaan (mencegah percobaan ke-4)
    //     if ($existingAttempts >= 3) {
    //         if ($round->type === 'logic') {
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => 'Kesempatan menjawab sudah habis (Maksimal 3 kali).'
    //             ], 422);
    //         }

    //         return back()->with('error', 'Kesempatan menjawab sudah habis.');
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | LOGIC
    //     |--------------------------------------------------------------------------
    //     */
    //     if ($round->type === 'logic') {
    //         try {
    //             $request->validate([
    //                 'question_number'  => 'required',
    //                 'round_setting_id' => 'required|exists:round_settings,id',
    //             ]);

    //             // 1. CEK RACE CONDITION (GLOBAL): Apakah soal ini sudah dijawab BENAR oleh peserta lain?
    //             $isAlreadySolved = Answer::where('round_setting_id', $request->round_setting_id)
    //                                      ->where('is_correct', true)
    //                                      ->exists();

    //             if ($isAlreadySolved) {
    //                 return response()->json([
    //                     'status'  => 'error',
    //                     'message' => 'Unfortunately, this question was just answered correctly by another participant.'
    //                 ], 422);
    //             }

    //             // 2. CEK PESERTA (SELF): Cek apakah peserta INI sudah pernah menjawab nomor soal ini (salah)
    //             $alreadyAnswered = Answer::where('participant_id', $request->participant_id)
    //                 ->where('round_id', $request->round_id)
    //                 ->where('round_setting_id', $request->round_setting_id)
    //                 ->exists();

    //             if ($alreadyAnswered) {
    //                 return response()->json([
    //                     'status'  => 'error',
    //                     'message' => 'Anda sudah pernah menjawab nomor soal ini.'
    //                 ], 422);
    //             }

    //             // 3. LOGIKA PENCOCOKAN: Ambil kunci jawaban dari round_setting_id
    //             $setting = RoundSetting::find($request->round_setting_id);
                    
    //             $userAnswer    = strtoupper(trim($request->answer));
    //             $correctAnswer = $setting ? strtoupper(trim($setting->correct_answer)) : '';

    //             $isCorrect = ($userAnswer === $correctAnswer);
    //             $score     = $isCorrect ? 1 : 0;

    //             // 4. Simpan ke database
    //             $answer = Answer::create([
    //                 'participant_id'   => $request->participant_id,
    //                 'round_id'         => $request->round_id,
    //                 'round_setting_id' => $setting ? $setting->id : null,
    //                 'answer'           => $request->answer,
    //                 'attempt'          => $attempt,
    //                 'is_correct'       => $isCorrect,
    //                 'score'            => $score,
    //             ]);

    //             return response()->json([
    //                 'status'     => 'success',
    //                 'is_correct' => $isCorrect,
    //                 'answer'     => $answer
    //             ]);

    //         } catch (\Exception $e) {
    //             // Tangkap error PHP/DB dan kembalikan sebagai JSON agar JS tidak crash
    //             return response()->json([
    //                 'status'  => 'error',
    //                 'message' => $e->getMessage()
    //             ], 500);
    //         }
    //     }
    //     /*
    //     |--------------------------------------------------------------------------
    //     | IMAGE SEQUENCE
    //     |--------------------------------------------------------------------------
    //     */
    //     elseif ($round->type === 'image_sequence') 
    //     {
    //         // Jawaban peserta
    //         $userAnswer = $request->answer;
    //         // dd($userAnswer);

    //         // Jawaban benar
    //         $correctAnswer = json_decode(
    //             $round->roundsetting->correct_answer,
    //             true
    //         );

    //         // Hitung jumlah gambar yang benar
    //         $correctCount = 0;

    //         // Hitung total gambar
    //         $totalImage = 0;

    //         foreach ($correctAnswer as $row => $columns) {
    //             foreach ($columns as $col => $correctImage) {
    //                 $totalImage++;

    //                 $userImage = $userAnswer[$row][$col] ?? null;

    //                 if ($userImage === $correctImage) {
    //                     $correctCount++;
    //                 }
    //             }
    //         }

    //         // Semua gambar benar
    //         $isCorrect = $correctCount === $totalImage;

    //         // Score = jumlah gambar benar
    //         $score = $correctCount;

    //     }
    //     else {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | STRING / NUMERIC / GENERAL ROUND TYPES
    //         |--------------------------------------------------------------------------
    //         */

    //         // Ambil jawaban peserta dan kunci dari database
    //         $rawUserAnswer = $request->answer;
    //         $rawCorrectAnswer = $round->roundsetting?->correct_answer ?? '';

    //         // Normalisasi: Ubah ke huruf kapital dan hapus spasi berlebih
    //         $userAnswer = strtoupper(trim($rawUserAnswer));
    //         $correctAnswer = strtoupper(trim($rawCorrectAnswer));

    //         // Bandingkan jawaban yang sudah dinormalisasi
    //         $isCorrect = ($userAnswer === $correctAnswer);
                    
    //         $score = $isCorrect ? 1 : 0;
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | SIMPAN JAWABAN
    //     |--------------------------------------------------------------------------
    //     */

    //     $roundSettingId = $round->roundSettings->first()?->id;

    //     $answer = Answer::create([
    //         'participant_id'   => $request->participant_id,
    //         'round_id'         => $request->round_id,
    //         'round_setting_id' => $roundSettingId,
    //         'answer'           => is_array($request->answer)
    //             ? json_encode($request->answer)
    //             : $request->answer,

    //         'attempt'    => $attempt,
    //         'is_correct' => $isCorrect,
    //         'score'      => $score,
    //     ]);

    //     return redirect()->route('answer.result', $answer->id);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'round_id'       => 'required|exists:rounds,id',
            'answer'         => 'required',
        ]);

        $round = Round::findOrFail($request->round_id);

        // ======================================================================
        // 1. REVISI CEK PERCOBAAN: Pisahkan hitungan per-papan untuk tipe 'logic'
        // ======================================================================
       $attemptQuery = Answer::where('participant_id', $request->participant_id)
            ->where('round_id', $request->round_id)
            ->where('is_correct', false);

        if ($round->type === 'logic') {
            $attemptQuery->where('session_number', $request->board_number);
        }

        $wrongAttempts = $attemptQuery->count();

        // Attempt saat ini = jumlah salah + 1
        $attempt = $wrongAttempts + 1;

        // Jika sudah 3 kali SALAH, tidak boleh menjawab lagi
        if ($wrongAttempts >= 3) {

            if ($round->type === 'logic') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your attempts for Board ' . $request->board_number . ' have run out. You have answered incorrectly 3 times.'
                ], 422);
            }

            return back()->with('error', 'Kesempatan menjawab sudah habis.');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIC
        |--------------------------------------------------------------------------
        */
        if ($round->type === 'logic') {
            try {
                $request->validate([
                    'question_number'  => 'required',
                    'round_setting_id' => 'required|exists:round_settings,id',
                    'board_number'     => 'required|numeric', // Tambahan validasi
                ]);

                // 1. CEK RACE CONDITION (GLOBAL)
                $isAlreadySolved = Answer::where('round_setting_id', $request->round_setting_id)
                                         ->where('is_correct', true)
                                         ->exists();

                if ($isAlreadySolved) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Unfortunately, this question was just answered correctly by another participant.'
                    ], 422);
                }

                // 2. CEK PESERTA (SELF)
                $alreadyAnswered = Answer::where('participant_id', $request->participant_id)
                    ->where('round_id', $request->round_id)
                    ->where('round_setting_id', $request->round_setting_id)
                    ->exists();

                if ($alreadyAnswered) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Anda sudah pernah menjawab nomor soal ini.'
                    ], 422);
                }

                // 3. LOGIKA PENCOCOKAN
                $setting = RoundSetting::find($request->round_setting_id);
                    
                $userAnswer    = strtoupper(trim($request->answer));
                $correctAnswer = $setting ? strtoupper(trim($setting->correct_answer)) : '';

                $isCorrect = ($userAnswer === $correctAnswer);
                $score     = $isCorrect ? 1 : 0;

                // ======================================================================
                // 2. REVISI CREATE: Simpan board_number ke session_number
                // ======================================================================
                $answer = Answer::create([
                    'participant_id'   => $request->participant_id,
                    'round_id'         => $request->round_id,
                    'round_setting_id' => $setting ? $setting->id : null,
                    'session_number'   => $request->board_number, // <--- TAMBAHAN INI
                    'answer'           => $request->answer,
                    'attempt'          => $attempt,
                    'is_correct'       => $isCorrect,
                    'score'            => $score,
                ]);

                return response()->json([
                    'status'     => 'success',
                    'is_correct' => $isCorrect,
                    'answer'     => $answer
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        /*
        |--------------------------------------------------------------------------
        | IMAGE SEQUENCE
        |--------------------------------------------------------------------------
        */
        elseif ($round->type === 'image_sequence') 
        {
            $userAnswer = $request->answer;
            $correctAnswer = json_decode($round->roundsetting->correct_answer, true);
            $correctCount = 0;
            $totalImage = 0;

            foreach ($correctAnswer as $row => $columns) {
                foreach ($columns as $col => $correctImage) {
                    $totalImage++;
                    $userImage = $userAnswer[$row][$col] ?? null;

                    if ($userImage === $correctImage) {
                        $correctCount++;
                    }
                }
            }
            $isCorrect = $correctCount === $totalImage;
            $score = $correctCount;
        }
        else {
            /*
            |--------------------------------------------------------------------------
            | STRING / NUMERIC / GENERAL ROUND TYPES
            |--------------------------------------------------------------------------
            */
            $rawUserAnswer = $request->answer;
            $rawCorrectAnswer = $round->roundsetting?->correct_answer ?? '';

            $userAnswer = strtoupper(trim($rawUserAnswer));
            $correctAnswer = strtoupper(trim($rawCorrectAnswer));

            $isCorrect = ($userAnswer === $correctAnswer);
            $score = $isCorrect ? 1 : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN JAWABAN (UNTUK NON-LOGIC)
        |--------------------------------------------------------------------------
        */

        $roundSettingId = $round->roundSettings->first()?->id;

        // ======================================================================
        // 3. REVISI CREATE: Beri nilai default session_number = 1 untuk ronde lain
        // ======================================================================
        $answer = Answer::create([
            'participant_id'   => $request->participant_id,
            'round_id'         => $request->round_id,
            'round_setting_id' => $roundSettingId,
            'session_number'   => 1, // <--- TAMBAHAN INI (Default agar tidak error DB)
            'answer'           => is_array($request->answer)
                ? json_encode($request->answer)
                : $request->answer,
            'attempt'          => $attempt,
            'is_correct'       => $isCorrect,
            'score'            => $score,
        ]);

        return redirect()->route('answer.result', $answer->id);
    }

public function result($id)
{
    $answer = Answer::findOrFail($id);

    $round = $answer->round;

    $totalImage = 0;

    if ($round->type === 'image_sequence') {

        $correctAnswer = json_decode(
            $round->roundsetting->correct_answer,
            true
        );

        foreach ($correctAnswer as $row => $columns) {
            foreach ($columns as $col => $image) {
                $totalImage++;
            }
        }
    }

    return view('bob.answers.result', compact(
        'answer',
        'totalImage'
    ));
}

}


