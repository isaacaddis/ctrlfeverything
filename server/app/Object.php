<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'obj';
    protected $fillable = [ 'name' ];

    public function imgs() {
        return $this->belongsToMany('App\Img');
    }
}
