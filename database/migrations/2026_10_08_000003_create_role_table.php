<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('role')) {
            return;
        }

        Schema::create('role', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->increments('roleID');
            $table->string('roleName')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
};
