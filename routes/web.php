<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/clear', function() {
    // Clear application cache
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return 'Caches cleared successfully.';
});


Route::get('/connection', function () {
    try {
        DB::connection()->getPdo();
        $message = 'Database connection is successful!';
        $status = true;
    } catch (QueryException $e) {
        $message = 'Database connection failed: ' . $e->getMessage();
        $status = false;
    }

    return response()->json([
        'status' => $status,
        'message' => $message
    ]);
});