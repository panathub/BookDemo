<?php

use App\Models\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rooms', 'slug')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique();
            });
        }
        if (! Schema::hasColumn('rooms', 'theme')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->string('theme')->default(Room::DEFAULT_THEME);
            });
        }

        $rows = DB::table('rooms')->whereNull('slug')->orderBy('RoomID')->get()
            ->sortBy(fn ($row) => isset(Room::LEGACY_PAGES_BY_ROOM_ID[$row->RoomID]) ? 0 : 1);
        foreach ($rows as $row) {
            [$slug, $theme] = Room::LEGACY_PAGES_BY_ROOM_ID[$row->RoomID]
                ?? [Room::slugFrom($row->RoomName) ?? Room::uniqueSlug("room-{$row->RoomID}"), Room::DEFAULT_THEME];
            DB::table('rooms')->where('RoomID', $row->RoomID)->update(['slug' => $slug, 'theme' => $theme]);
        }
    }

    public function down(): void
    {
        if (app()->isProduction()) {
            throw new RuntimeException('refusing to drop production tables');
        }

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'theme']);
        });
    }
};
