<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FileManagerController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login Routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [FileManagerController::class, 'index'])
        ->name('files.index');

    Route::post('/upload', [FileManagerController::class, 'upload'])
        ->name('files.upload');

    Route::post('/folder', [FileManagerController::class, 'createFolder'])
        ->name('files.folder');

    Route::delete('/delete', [FileManagerController::class, 'delete'])
        ->name('files.delete');

    Route::post('/folder/info', [FileManagerController::class, 'folderInfo'])
        ->name('files.folderInfo');        

    Route::post('/files/invalidate', [FileManagerController::class, 'invalidate'])
        ->name('files.invalidate');

    Route::post('/files/invalidate-folder', [FileManagerController::class, 'invalidateFolder'])
        ->name('files.invalidateFolder');

    Route::get('/files/local', [FileManagerController::class, 'local'])
        ->name('files.local');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::delete('/folder', [FileManagerController::class, 'deleteFolder'])->name('files.deleteFolder');
});



