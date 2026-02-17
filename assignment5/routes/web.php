<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/evaluation', function (Request $request) {
    return view('evaluation', [
        'name' => $request->name,
        'prelim' => $request->prelim,
        'midterm' => $request->midterm,
        'final' => $request->final,
        'submit' => $request->has('name')
    ]);
});