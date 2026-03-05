<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagerController;

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


Route::match(['get','post'], '/', [FileManagerController::class, 'index'])
    ->name('files.index');

Route::get('/files/local', [FileManagerController::class, 'local'])
    ->name('files.local');
    
Route::post('/files/invalidate', [FileManagerController::class, 'invalidate'])
    ->name('files.invalidate');

Route::post('/files/invalidate-folder', [FileManagerController::class, 'invalidateFolder'])
    ->name('files.invalidateFolder');
        
Route::delete('/delete', [FileManagerController::class, 'delete'])
    ->name('files.delete');
