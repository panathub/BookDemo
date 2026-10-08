<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('department')) {
            return;
        }

        Schema::create('department', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->increments('DepartmentID');
            $table->string('DepartmentName');
        });
    }

    public function down()
    {
        Schema::dropIfExists('department');
    }
};
