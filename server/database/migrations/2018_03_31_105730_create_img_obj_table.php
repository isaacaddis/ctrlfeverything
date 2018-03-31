<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImgObjTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('img_obj', function (Blueprint $table) {
            $table->integer('img_id')->unsigned();
            $table->integer('obj_id')->unsigned();
            $table->timestamps();

            $table->primary([ 'img_id', 'obj_id' ]);
            $table->foreign('img_id')->references('id')->on('img');
            $table->foreign('obj_id')->references('id')->on('obj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('img_obj');
    }
}
