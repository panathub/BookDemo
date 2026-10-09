<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    public const DEFAULT_THEME = 'linear-gradient(to right, #373b44, #4286f4)';

    public const LEGACY_PAGES_BY_ROOM_ID = [
        60 => ['tonkotsu', 'linear-gradient(90deg, #00DBDE 0%, #FC00FF 100%)'],
        63 => ['karamiso', 'linear-gradient(to right, #ff512f, #dd2476)'],
        64 => ['sukiyaki', 'linear-gradient(120deg, #f093fb 0%, #f5576c 100%)'],
        66 => ['shabushabu', 'linear-gradient(45deg, #FA8BFF 0%, #2BD2FF 52%, #2BFF88 90%)'],
        67 => ['kinoko', 'linear-gradient(225deg, #FF3CAC 0%, #784BA0 50%, #2B86C5 100%)'],
        78 => ['ponzu', self::DEFAULT_THEME],
        79 => ['oil-sauce', self::DEFAULT_THEME],
        80 => ['sesame', self::DEFAULT_THEME],
        81 => ['sweet-shoyu', self::DEFAULT_THEME],
        82 => ['warishita', self::DEFAULT_THEME],
    ];

    protected $table = 'rooms';

    protected $primaryKey = 'RoomID';

    public $timestamps = false;

    protected $fillable = ['RoomName', 'RoomNumber', 'RoomStatus', 'RoomAmount', 'Image_room', 'slug', 'theme'];

    protected static function booted(): void
    {
        static::creating(function (Room $room) {
            $room->slug ??= static::slugFrom($room->RoomName);
        });
    }

    public static function slugFrom(string $name): string
    {
        $base = Str::slug($name) ?: 'room';
        $slug = $base;
        for ($n = 2; static::where('slug', $slug)->exists(); $n++) {
            $slug = "$base-$n";
        }

        return $slug;
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'RoomID', 'RoomID');
    }
}
