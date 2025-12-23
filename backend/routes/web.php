<?php

use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/ccb_project.html');
});

// Web routes (static HTML pages are served from public directory)

