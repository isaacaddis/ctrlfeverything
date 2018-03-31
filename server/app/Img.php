<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    //
    protected $table = 'img';
    protected $fillable = [ 'img', 'ip', 'takenAt', 'deviceName' ];

    public function objs() {
        return $this->belongsToMany('App\\AppObject', 'img_obj', 'img_id', 'obj_id');
    }
}
