<?php

namespace App\Http\Requests;

use App\Models\BookingSlot;
use App\Models\Room;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'BookingTitle' => 'required|string|max:255',
            'RoomID' => 'required|integer|exists:rooms,RoomID',
            'BookingAmount' => 'required|integer|min:1',
            'BookingDetail' => 'nullable|string|max:255',
            'Booking_start' => 'required|date|after_or_equal:now',
            'Booking_end' => 'required|date|after:Booking_start',
        ];
    }

    public function messages(): array
    {
        return [
            'RoomID.integer' => 'ไม่พบห้องประชุม',
            'RoomID.exists' => 'ไม่พบห้องประชุม',
            'Booking_start.after_or_equal' => 'วันที่เริ่มต้นการจองต้องเป็นวันที่นับจากนี้เป็นต้นไป',
            'Booking_end.after' => 'วันที่สิ้นสุดการจองจะต้องอยู่หลังวันที่เริ่มต้นการจอง',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]));
    }

    public function slot(): BookingSlot
    {
        return BookingSlot::parse($this->input('Booking_start'), $this->input('Booking_end'));
    }

    public function room(): Room
    {
        return Room::findOrFail($this->integer('RoomID'));
    }

    /** @return array{BookingTitle: string, BookingAmount: int, BookingDetail: ?string} */
    public function attrs(): array
    {
        return [
            'BookingTitle' => $this->input('BookingTitle'),
            'BookingAmount' => $this->integer('BookingAmount'),
            'BookingDetail' => $this->input('BookingDetail'),
        ];
    }
}
