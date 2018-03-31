<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Img;
use App\Http\Resources\ImgResource;

class ImgController extends Controller
{
    public function getByTakenAfter(Request $request) {
        $takenAfter = $request->query('taken_after');
        if(!is_numeric($takenAfter)) {
            return response('', 400);
        }
        return ImgResource::collection(Img::where('takenAt', '>=', intval($takenAfter))->get());
    }

    public function create(Request $request) {
        $img = new Img;
        $img->img = $request->img;
        $img->ip = $request->ip;
        $img->takenAt = intval(floor($request->takenAt));
        $img->deviceName = $request->deviceName;
        $img->save();
    }

}
