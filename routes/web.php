<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ParticipantEntryController;

Route::get('/phpinfo', function () {
    phpinfo();
});
Route::get('/', [ParticipantEntryController::class, 'index'])
    ->name('entry.form');

Route::get('/answer/result/{answer}', [AnswerController::class, 'result'])
    ->name('answer.result');

Route::get('/answer/{participant}/{round}', 
    [AnswerController::class, 'show']
)->name('answer.show');

Route::post('/answer', [AnswerController::class, 'store'])
    ->name('answer.store');

Route::post('/entry', [ParticipantEntryController::class, 'check'])
    ->name('entry.check');

Route::get('/round/{round}/logical-statuses', [RoundController::class, 'logicalStatuses'])
    ->name('round.logical-statuses');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::resource('participants', ParticipantController::class);
    Route::resource('rounds', RoundController::class);

    Route::get('/round/{id}/answers', [RoundController::class, 'getAnswers']);
    Route::post('/rounds/{id}/activate', [RoundController::class, 'activate']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post(
    '/round/{round}/spatial/next',
    [RoundController::class, 'nextSpatial']
)->name('round.spatial.next');

// Route untuk Admin mengontrol navigasi soal
// Route::post('/round/{id}/spatial/control', [RoundController::class, 'controlSpatial']);
// Route untuk Admin mengontrol navigasi soal (Sesuai dengan Fetch JS)
Route::post('/round/{id}/spatial-control', [RoundController::class, 'spatialControl']);

// Route untuk Siswa mengecek apakah soal sudah diganti Admin secara otomatis
Route::get('/round/{id}/active-question', [RoundController::class, 'getActiveQuestion']);

// Route untuk Siswa mengirimkan jawaban via AJAX
Route::post('/spatial/submit-answer', [RoundController::class, 'submitSpatialAnswer']);