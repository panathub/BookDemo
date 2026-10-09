<?php

namespace App\Exceptions;

use App\Models\Room;
use DomainException;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;

final class BookingRefused extends DomainException implements ShouldntReport
{
    private function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
    }

    public static function overCapacity(Room $room): self
    {
        return new self(2, "ห้อง {$room->RoomName} จำนวนคนต้องไม่เกิน {$room->RoomAmount} คน");
    }

    public static function overlap(): self
    {
        return new self(3, 'มีการจองช่วงเวลานี้อยู่แล้ว');
    }

    public static function pendingOverlap(): self
    {
        return new self(2, 'มีการจองรอยืนยัน');
    }

    public static function notEditable(): self
    {
        return new self(2, 'ไม่สามารถแก้ไขหรือลบการจองของผู้อื่นหรือการจองที่อนุมัติแล้ว');
    }

    public function render(): JsonResponse
    {
        return response()->json(['code' => $this->getCode(), 'msg' => $this->getMessage()]);
    }
}
