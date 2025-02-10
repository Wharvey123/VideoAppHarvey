<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideosManageController extends Controller
{
    public function manage()
    {
        // Comprova si l'usuari té permís per gestionar vídeos
        if (auth()->user()->can('manage-videos')) {
            return response()->json(['message' => 'User can manage videos.'], 200);
        }
        abort(403, 'Unauthorized');
    }
}
