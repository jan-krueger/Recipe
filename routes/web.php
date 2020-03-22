<?php

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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/experiment/get/{id}', function (string $experiment_id) {
    $path = $experiment_id . '.data';
    if(Storage::exists($path))
    {
        return response()->json(json_decode(Storage::get($path), true));
    }
    else
    {
        return response([]);
    }
});

Route::post('/experiment/save/{id}', function (Request $request, string $experiment_id) {
    $path = $experiment_id . '.data';
    Storage::put($path, json_encode($request->get('data')));
    return response([]);
});
