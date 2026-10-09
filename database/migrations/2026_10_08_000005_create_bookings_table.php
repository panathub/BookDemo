<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
            $table->increments('BookingID');
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
            $table->unsignedInteger('ReportID')->nullable();
            $table->softDeletes();

            $table->index('id', 'foreign key id');
            $table->index('RoomID', 'foreign key RoomID');
            $table->index('ReportID', 'foreign key RPID');
            $table->foreign('ReportID', 'foreign key RPID')->references('ReportID')->on('reports')->nullOnDelete();
            $table->foreign('RoomID', 'foreign key RoomID')->references('RoomID')->on('rooms')->cascadeOnDelete();
            $table->foreign('id', 'foreign key id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        if (app()->isProduction()) {
            throw new RuntimeException('refusing to drop production tables');
        }

        Schema::dropIfExists('bookings');
    }
};
