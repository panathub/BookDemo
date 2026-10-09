<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('reports')) {
            return;
        }

        Schema::create('reports', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->increments('ReportID');
            $table->string('BookingTitle');
            $table->integer('BookingAmount')->nullable();
            $table->dateTime('Booking_start');
            $table->dateTime('Booking_end');
            $table->string('BookingDetail')->nullable()->default('--');
            $table->integer('BookingStatus')->nullable()->default(0);
            $table->integer('RoomStatus')->nullable()->default(0);
            $table->integer('VerifyStatus')->nullable()->default(0);
            $table->unsignedBigInteger('id')->nullable();
            $table->unsignedInteger('RoomID')->nullable();

            $table->index('RoomID', 'foreign key RID');
            $table->index('id', 'foreign key UID');
            $table->foreign('RoomID', 'foreign key RID')->references('RoomID')->on('rooms')->nullOnDelete();
            $table->foreign('id', 'foreign key UID')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        if (app()->isProduction()) {
            throw new RuntimeException('refusing to drop production tables');
        }

        Schema::dropIfExists('reports');
    }
};
