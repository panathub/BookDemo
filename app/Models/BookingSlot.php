<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final readonly class BookingSlot
{
    public function __construct(public CarbonImmutable $start, public CarbonImmutable $end)
    {
        if ($end <= $start) {
            throw new InvalidArgumentException('a booking must end after it starts');
        }
    }

    public static function parse(string $start, string $end): self
    {
        return new self(CarbonImmutable::parse($start), CarbonImmutable::parse($end));
    }

    /** @return array{Booking_start: string, Booking_end: string} */
    public function columns(): array
    {
        return [
            'Booking_start' => $this->start->format('Y-m-d H:i:s'),
            'Booking_end' => $this->end->format('Y-m-d H:i:s'),
        ];
    }
}
