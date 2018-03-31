<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ObjectResourceCollection;
use App\AppObject;

class ObjectController extends Controller
{
    public function all() {
        return new ObjectResourceCollection(AppObject::all());
    }
}
