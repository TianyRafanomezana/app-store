<?php

use App\Http\Controllers\HabitController;
use App\Http\Controllers\YoutubeVideoController;
use Illuminate\Support\Facades\Route;

Route::get('/youtube/search', [YoutubeVideoController::class, 'search']);


