<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('modal')) {
            return;
        }

        Schema::create('modal', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->integer('id', true);
            $table->string('image')->nullable();
            $table->longText('text')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modal');
    }
};
