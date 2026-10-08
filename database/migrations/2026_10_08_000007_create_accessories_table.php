<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('accessories')) {
            return;
        }

        Schema::create('accessories', function (Blueprint $table) {
            $table->increments('AccessoriesID');
            $table->string('Name');
            $table->integer('Quantity');
            $table->string('Image_acc');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accessories');
    }
};
