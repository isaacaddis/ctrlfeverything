<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImgObject extends Model
{
    //
    protected $table = 'img_obj';
    protected $fillable = [ 'img_id', 'obj_id' ];
}
