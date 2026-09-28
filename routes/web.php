<?php

use App\Http\Controllers\CabanaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CulturaController;
use App\Http\Controllers\EntornoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PutreController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

$rutasPublicas = function () {
    Route::get('/', InicioController::class)->name('inicio');
    Route::get('/cultura', CulturaController::class)->name('cultura');
    Route::get('/putre', PutreController::class)->name('putre');
    Route::get('/cabanas', [CabanaController::class, 'index'])->name('cabanas.index');
    Route::get('/cabanas/{cabana:slug}', [CabanaController::class, 'show'])->name('cabanas.show');
    Route::get('/entorno', [EntornoController::class, 'index'])->name('entorno');
    Route::get('/entorno/{lugar:slug}', [EntornoController::class, 'show'])->name('entorno.show');
    Route::view('/nosotros', 'nosotros')->name('nosotros');
    Route::get('/contacto', ContactoController::class)->name('contacto');
    Route::get('/reserva', ReservaController::class)->name('reserva');
};

$rutasPublicas();
Route::prefix('en')->name('en.')->group($rutasPublicas);
Route::prefix('fr')->name('fr.')->group($rutasPublicas);

Route::redirect('/dashboard', '/admin')->middleware(['auth', 'admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
