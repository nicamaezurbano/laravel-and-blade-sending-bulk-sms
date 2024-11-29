<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TextBeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::patch('/contacts/{contact_id}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact_id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('/receive-sms', [TextBeeController::class, 'receiveSMS'])->name('sms.receive');
    Route::get('/send-sms', [TextBeeController::class, 'sendSMS_index'])->name('sms.send.index');
    Route::post('/send-sms', [TextBeeController::class, 'sendSMS'])->name('sms.send');
});

require __DIR__.'/auth.php';
