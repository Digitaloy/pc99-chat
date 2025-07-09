<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeminiChatController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

Route::get('/gemini-models', function () {
    $apiKey = env('GEMINI_API_KEY');

    $response = Http::get("https://generativelanguage.googleapis.com/v1/models?key={$apiKey}");

    Log::info('Gemini Models List:', $response->json());

    return $response->json();
});




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [GeminiChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [GeminiChatController::class, 'send'])->name('chat.send');
});


require __DIR__.'/auth.php';
