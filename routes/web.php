<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\CodeController;
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
    return redirect('/login');
});



Route::get('/dashboard', [AdminController::class,'dashboard'])->middleware(['auth'])->name('dashboard');


Route::prefix('door')->middleware(['auth'])->name('door.')->group(function () {
    Route::get('/index', [DoorController::class,'index'])->middleware(['auth'])->name('index');
    Route::get('/list', [DoorController::class,'list'])->middleware(['auth'])->name('list');
    Route::get('/detail/{id}', [DoorController::class,'detail'])->middleware(['auth'])->name('detail');
    //Route::get('/create', [DoorController::class,'create'])->middleware(['auth'])->name('create');
    Route::post('/create', [DoorController::class,'create'])->middleware(['auth'])->name('create.submit');
    Route::post('/edit/{id}', [DoorController::class,'edit'])->middleware(['auth'])->name('edit');
});

Route::prefix('code')->middleware(['auth'])->name('code.')->group(function () {
    Route::get('/index', [CodeController::class,'index'])->middleware(['auth'])->name('index');
    Route::get('/list', [CodeController::class,'list'])->middleware(['auth'])->name('list');
    Route::get('/detail/{id}', [CodeController::class,'detail'])->middleware(['auth'])->name('detail');
    //Route::get('/create', [CodeController::class,'create'])->middleware(['auth'])->name('create');
    Route::post('/edit/{id}', [CodeController::class,'edit'])->middleware(['auth'])->name('edit');
    Route::get('/history', [CodeController::class,'history'])->middleware(['auth'])->name('history');
    Route::post('/generate', [CodeController::class,'generate'])->middleware(['auth'])->name('generate');
    Route::post('/check', [CodeController::class,'checkNumber'])->middleware(['auth'])->name('generate');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
