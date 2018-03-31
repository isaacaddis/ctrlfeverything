<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    //
    protected $table = 'img';
    protected $fillable = [ 'img', 'ip', 'takenAt', 'deviceName' ];
}
