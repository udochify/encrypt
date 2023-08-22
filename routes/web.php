<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\ProfileController;
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

    Route::resource('files', FileController::class)->only(['index', 'create', 'store', 'destroy'])
    ->names(['index'=>'files.index', 'create'=>'files.create', 'store'=>'files.store', 'destroy'=>'files.delete']);
    Route::post('/files/decrypt/{file}', [FileController::class, 'decrypt_file'])->name('files.decrypt');
    Route::delete('/files/delete/decrypted/{file}', [FileController::class, 'delete_decrypted'])->name('files.delete.decrypted');

    Route::get('/gen-aes-key', [FileController::class, 'generate_aes_key'])->name('file.keygen.aes');
    
    Route::resource('keys', KeyController::class)->only(['index', 'create', 'store'])
    ->names(['index'=>'keys.index', 'create'=>'keys.create', 'store'=>'keys.store']);
});

require __DIR__.'/auth.php';
