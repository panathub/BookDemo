<?php

namespace Tests\Feature;

use App\Enums\BookingState;
use App\Models\Bookings;
use App\Models\BookingSlot;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Tests\TestCase;

class BookingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    private function slot(int $startHour, int $startMinute = 0, int $lengthMinutes = 60): array
    {
        $start = Carbon::tomorrow()->addDays(3)->setTime($startHour, $startMinute);

        return [
            'Booking_start' => $start->format('Y-m-d H:i:s'),
            'Booking_end' => $start->copy()->addMinutes($lengthMinutes)->format('Y-m-d H:i:s'),
        ];
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'BookingTitle' => 'Lifecycle test',
            'RoomID' => 63,
            'BookingAmount' => 4,
            'BookingDetail' => '--',
        ], $this->slot(10), $overrides);
    }

    private function otherUser(): User
    {
        return User::create(['name' => 'Other', 'email' => 'other@example.test', 'password' => bcrypt('password'), 'roleID' => 2, 'DepartmentID' => 2]);
    }

    private function requested(): Bookings
    {
        $slot = BookingSlot::parse(...array_values($this->slot(10)));

        return Bookings::request($this->user, Room::find(63), $slot, ['BookingTitle' => 'Module test', 'BookingAmount' => 4, 'BookingDetail' => null]);
    }

    public function test_overlapping_request_is_refused_with_the_thai_message(): void
    {
        $this->actingAs($this->user)->post('/user/add-booking', $this->payload())
            ->assertJson(['code' => 1, 'msg' => 'เพิ่มการจองเรียบร้อย']);

        $response = $this->actingAs($this->user)->post('/user/add-booking', $this->payload($this->slot(10, 30)));

        $response->assertJson(['code' => 3, 'msg' => 'มีการจองช่วงเวลานี้อยู่แล้ว']);
        $this->assertSame(1, Bookings::where('BookingTitle', 'Lifecycle test')->count());
    }

    public function test_request_over_room_capacity_is_refused(): void
    {
        $response = $this->actingAs($this->user)->post('/user/add-booking', $this->payload(['BookingAmount' => 25]));

        $response->assertJson(['code' => 2, 'msg' => 'ห้อง Karamiso จำนวนคนต้องไม่เกิน 9 คน']);
        $this->assertSame(0, Bookings::where('BookingTitle', 'Lifecycle test')->count());
    }

    public function test_sql_injection_in_room_id_is_rejected_by_validation_and_writes_nothing(): void
    {
        $before = Bookings::withTrashed()->count();

        $response = $this->actingAs($this->user)->post('/user/add-booking', $this->payload(['RoomID' => "63' OR '1'='1"]));

        $response->assertJson(['code' => 0]);
        $this->assertArrayHasKey('RoomID', $response->json('error'));
        $this->assertSame($before, Bookings::withTrashed()->count());
    }

    public function test_request_writes_the_report_mirror_and_starts_requested(): void
    {
        $booking = $this->requested();

        $this->assertSame(BookingState::Requested, $booking->state);
        $this->assertSame(0, $booking->VerifyStatus);
        $this->assertSame('Module test', $booking->report->BookingTitle);
        $this->assertSame($booking->Booking_start, $booking->report->Booking_start);
    }

    public function test_approve_then_expire_after_booking_end(): void
    {
        $booking = $this->requested();

        $booking->approve();
        DB::enableQueryLog();
        $booking->approve();
        $this->assertCount(0, DB::getQueryLog());

        $fresh = $booking->fresh();
        $this->assertSame(BookingState::Approved, $fresh->state);
        $this->assertSame([1, 2, 1], [$fresh->VerifyStatus, $fresh->RoomStatus, $fresh->BookingStatus]);

        $this->assertSame(0, Bookings::expireDue());

        $this->travelTo(CarbonImmutable::parse($booking->Booking_end)->addMinute());
        $this->assertGreaterThanOrEqual(1, Bookings::expireDue());
        $this->assertSame(0, Bookings::expireDue());

        $expired = Bookings::withTrashed()->find($booking->BookingID);
        $this->assertSame(BookingState::Expired, $expired->state);
        $this->assertNotNull($expired->deleted_at);
        $this->assertSame(1, $expired->VerifyStatus);
    }

    public function test_admin_cancel_sets_verify_status_two_and_soft_deletes(): void
    {
        $booking = $this->requested();

        $this->actingAs($this->admin)->post('/admin/cancleBookingDetails', ['booking_id' => $booking->BookingID])
            ->assertJson(['code' => 1, 'msg' => 'ยกเลิกการจองเรียบร้อย']);

        $cancelled = Bookings::withTrashed()->find($booking->BookingID);
        $this->assertSame(BookingState::Cancelled, $cancelled->state);
        $this->assertSame(2, $cancelled->VerifyStatus);
        $this->assertNotNull($cancelled->deleted_at);
        $this->assertEquals($cancelled->deleted_at, $cancelled->cancel()->deleted_at);
    }

    public function test_admin_approve_runs_without_a_queue(): void
    {
        $this->actingAs($this->admin)->post('/admin/verifyBookingDetails', ['booking_id' => 3])
            ->assertJson(['code' => 1, 'msg' => 'อนุมัตการจองเรียบร้อย']);

        $this->assertSame(BookingState::Approved, Bookings::find(3)->state);
    }

    public function test_two_day_purge_cancels_only_unverified_bookings(): void
    {
        $stale = Carbon::now()->subDays(3)->setTime(9, 0);
        foreach ([[101, 0], [102, 1]] as [$id, $verified]) {
            DB::table('bookings')->insert([
                'BookingID' => $id, 'BookingTitle' => "Stale $id", 'BookingAmount' => 2, 'id' => 2, 'RoomID' => 60,
                'Booking_start' => $stale, 'Booking_end' => $stale->copy()->addHour(), 'VerifyStatus' => $verified,
            ]);
        }

        $this->artisan('cron:deletebooking')->assertSuccessful();

        $this->assertSame(BookingState::Cancelled, Bookings::withTrashed()->find(101)->state);
        $this->assertSame(BookingState::Approved, Bookings::find(102)->state);
        $this->assertSame(BookingState::Requested, Bookings::find(3)->state);
    }

    public function test_user_can_reschedule_an_own_pending_booking_and_the_report_follows(): void
    {
        $booking = $this->requested();

        $this->actingAs($this->user)->post('/user/updateUserBookingDetails', $this->payload(['bkid' => $booking->BookingID, 'BookingTitle' => 'Moved'] + $this->slot(15)))
            ->assertJson(['code' => 1, 'msg' => 'อัพเดทการจองเรียบร้อย']);

        $moved = $booking->fresh();
        $this->assertSame('Moved', $moved->BookingTitle);
        $this->assertSame($this->slot(15)['Booking_start'], $moved->Booking_start);
        $this->assertSame($this->slot(15)['Booking_start'], $moved->report->Booking_start);
    }

    public function test_user_cannot_update_another_users_booking(): void
    {
        $response = $this->actingAs($this->otherUser())->post('/user/updateUserBookingDetails', $this->payload(['bkid' => 3, 'rpid' => 3, 'BookingTitle' => 'Hijacked']));

        $response->assertOk()->assertExactJson(['code' => 2, 'msg' => 'ไม่สามารถแก้ไขหรือลบการจองของผู้อื่นหรือการจองที่อนุมัติแล้ว']);
        $this->assertSame('Demo review', Bookings::find(3)->BookingTitle);
    }

    public function test_user_cannot_delete_another_users_booking(): void
    {
        $this->actingAs($this->otherUser())->post('/user/deleteUserBooking', ['booking_id' => 3])
            ->assertOk()->assertExactJson(['code' => 2, 'msg' => 'ไม่สามารถแก้ไขหรือลบการจองของผู้อื่นหรือการจองที่อนุมัติแล้ว']);

        $this->assertNotNull(Bookings::find(3));
    }

    public function test_guest_post_to_verify_meeting_redirects_to_login(): void
    {
        $this->post('/verifyMeeting', ['bkid' => 1])->assertRedirect('/login');
        $this->assertSame(0, Bookings::find(1)->BookingStatus);
    }

    public function test_guest_post_to_update_modal_details_redirects_to_login(): void
    {
        $this->post('/updateModalDetails', ['mid' => 1, 'text' => 'defaced'])->assertRedirect('/login');
    }

    public function test_logged_in_kiosk_confirm_sets_booking_status(): void
    {
        $this->actingAs($this->user)->post('/verifyMeeting', ['bkid' => 1])
            ->assertJson(['code' => 1, 'msg' => 'ยืนยันการจองห้องประชุม']);

        $this->assertSame(1, Bookings::find(1)->BookingStatus);
    }

    public function test_slot_must_end_after_it_starts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        BookingSlot::parse('2026-10-20 10:00:00', '2026-10-20 10:00:00');
    }
}
