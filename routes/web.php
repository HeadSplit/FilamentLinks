<?php

use App\Http\Controllers\ClickController;
use Illuminate\Support\Facades\Route;
use App\Models\Link;


Route::get('/{short_url}', ClickController::class);
