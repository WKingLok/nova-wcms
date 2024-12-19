<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:administrators'])->group(function () {
    //
});
