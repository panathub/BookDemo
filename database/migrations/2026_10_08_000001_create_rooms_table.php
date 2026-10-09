<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('rooms')) {
            return;
        }

        Schema::create('rooms', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->increments('RoomID');
            $table->string('RoomName');
            $table->string('RoomNumber');
            $table->integer('RoomAmount')->nullable();
            $table->integer('RoomStatus')->nullable();
            $table->string('Image_room');
        });
    }

    public function down()
    {
        if (app()->isProduction()) {
            throw new RuntimeException('refusing to drop production tables');
        }

        Schema::dropIfExists('rooms');
    }
};
