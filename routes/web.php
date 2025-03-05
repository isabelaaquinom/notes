<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo "hiiii";
});

Route::get('/about', function () {
    echo "about us";
});