<?php

use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(
    [
        'prefix' => '{locale}',
        'where' => [
            'locale' => implode('|', config('localization.availableLocales')), // 'ru|en'
        ],
    ],
    function () {
        Route::get('/dashboard', function () {
            \App\Models\User::query()->lazy(5);

            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::name('adminTasks.')->prefix('admin')->group(function () {
        Route::resource('/tasks', TaskController::class);
    })->middleware('auth');
});

require __DIR__ . '/auth.php';
