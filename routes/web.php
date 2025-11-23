<?php

use App\Http\Controllers\Admin\TaskController;
use Illuminate\Support\Facades\Route;

Route::name('adminTasks.')->prefix('admin')->group(function () {
    Route::resource('/tasks', TaskController::class);
});
