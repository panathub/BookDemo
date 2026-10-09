<?php

namespace App\Exports;

use App\Models\Bookings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $room;

    public function __construct($startDate, $endDate, $room)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->room = $room;
    }
    public function collection(): Collection
    {
        $query = Bookings::query()
            ->with(['user.department', 'room'])
            ->withTrashed()
            ->whereBetween('Booking_start', [$this->startDate, $this->endDate])
            ->orderBy('Booking_start', 'asc');

            if ($this->room) {
                $query->whereHas('room', function ($query) {
                    $query->where('RoomName', $this->room);
                });
            }

            return $query->get();

    }

    public function map($booking): array
    {
        $verifyStatus = ['รอยืนยัน', 'อนุมัติแล้ว', 'ไม่อนุมัติ'];

        return [
            $booking->id,
            $booking->room->RoomName,
            $booking->user->name,
            $booking->user->department->DepartmentName ?? null,
            $booking->Booking_start,
            $booking->Booking_end,
            $booking->BookingTitle,
            $booking->BookingAmount,
            $booking->BookingDetail,
            $verifyStatus[$booking->VerifyStatus],

        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'ห้องประชุม',
            'ผู้จอง',
            'แผนก',
            'วัน/เวลาเริ่ม',
            'วัน/เวลาสิ้นสุด',
            'หัวข้อการประชุม',
            'จำนวนผู้เข้าประชุม',
            'รายละเอียดการประชุม',
            'สถานะการอนุมัติ'
        ];
    }
}