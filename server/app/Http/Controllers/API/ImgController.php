<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Img;

class ImgController extends Controller
{
    public function getByTakenAfter(Request $request) {
        $takenAfter = $request->query('taken_after');
        if($takenAfter) {
        }
    }
}
