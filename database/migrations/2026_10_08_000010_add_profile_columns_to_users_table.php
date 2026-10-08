<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'roleID')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('picture', 191)->nullable()->after('email');
            $table->unsignedInteger('DepartmentID')->nullable();
            $table->unsignedInteger('roleID')->nullable();

            $table->index('roleID', 'foreign key roleID');
            $table->index('DepartmentID', 'foreign key DepartmentID');
            $table->foreign('DepartmentID', 'foreign key DepartmentID')->references('DepartmentID')->on('department')->cascadeOnDelete();
            $table->foreign('roleID', 'foreign key roleID')->references('roleID')->on('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('foreign key DepartmentID');
            $table->dropForeign('foreign key roleID');
            $table->dropIndex('foreign key roleID');
            $table->dropIndex('foreign key DepartmentID');
            $table->dropColumn(['picture', 'DepartmentID', 'roleID']);
        });
    }
};
