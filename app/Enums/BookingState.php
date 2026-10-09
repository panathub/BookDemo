<?php

namespace App\Enums;

use App\Models\Bookings;

enum BookingState: string
{
    case Requested = 'requested';
    case Approved = 'approved';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public static function of(Bookings $booking): self
    {
        return match (true) {
            $booking->VerifyStatus === 2 => self::Cancelled,
            $booking->trashed() => self::Expired,
            $booking->VerifyStatus === 1 => self::Approved,
            default => self::Requested,
        };
    }

    /** @return array<string, int> */
    public function columns(): array
    {
        return match ($this) {
            self::Requested => ['VerifyStatus' => 0, 'RoomStatus' => 1],
            self::Approved => ['VerifyStatus' => 1, 'RoomStatus' => 2, 'BookingStatus' => 1],
            self::Cancelled => ['VerifyStatus' => 2],
            self::Expired => ['VerifyStatus' => 1],
        };
    }

    public function trashed(): bool
    {
        return $this === self::Cancelled || $this === self::Expired;
    }

    public function canBecome(self $to): bool
    {
        $edges = match ($this) {
            self::Requested => [self::Approved, self::Cancelled],
            self::Approved => [self::Cancelled, self::Expired],
            self::Cancelled, self::Expired => [],
        };

        return in_array($to, $edges, true);
    }
}
