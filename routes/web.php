<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(
        [
            'app' => config('app.name'),
            'developer' => "@saddamhshovon"
        ],
        Response::HTTP_OK
    );
});
