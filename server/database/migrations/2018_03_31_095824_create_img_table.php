<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('img', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('img');
            $table->ipAddress('ip');
            $table->unsignedBigInteger('takenAt');
            $table->string('deviceName', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('img');
    }
}
