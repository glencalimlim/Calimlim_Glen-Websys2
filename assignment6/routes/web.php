<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/student/{id}/{name}', function ($id, $name) {
    return view('student', [
        'id' => $id,
        'name' => $name
    ]);
});

Route::get('/course/{course}/{year?}', function ($course, $year = 'Not Specified') {
    return view('course', [
        'course' => $course,
        'year' => $year
    ]);
});
Route::get('/ojt/{company}/{city}/{allowance?}', function ($company, $city, $allowance = 'No') {
    return view('ojt', [
        'company' => $company,
        'city' => $city,
        'allowance' => $allowance
    ]);
});
Route::get('/event/{event}/{participant}/{year}', function ($event, $participant, $year) {
    return view('event', [
        'event' => $event,
        'participant' => $participant,
        'year' => $year
    ]);
});