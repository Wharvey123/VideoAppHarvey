<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    // Imprimir la cita directament amb la funció echo o comandes alternatives
    echo Inspiring::quote();
})->purpose('Display an inspiring quote')->hourly();
