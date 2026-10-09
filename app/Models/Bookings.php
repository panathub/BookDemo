<?php

namespace App\Models;

use App\Enums\BookingState;
use App\Exceptions\BookingRefused;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LogicException;

class Bookings extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $primaryKey = 'BookingID';

    public $timestamps = false;

    protected $fillable = [
        'BookingTitle',
        'BookingAmount',
        'Booking_start',
        'Booking_end',
        'BookingDetail',
        'id',
        'RoomID',
        'ReportID',
    ];

    protected $casts = [
        'id' => 'integer',
        'BookingStatus' => 'integer',
        'RoomStatus' => 'integer',
        'VerifyStatus' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomID');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'ReportID');
    }

    /**
     * @param  array{BookingTitle: string, BookingAmount: int, BookingDetail: ?string}  $attrs
     *
     * @throws BookingRefused
     */
    public static function request(User $user, Room $room, BookingSlot $slot, array $attrs): static
    {
        return DB::transaction(function () use ($user, $room, $slot, $attrs) {
            static::refuseUnlessFits($room, $slot, $attrs['BookingAmount']);

            $columns = $attrs + $slot->columns() + ['id' => $user->id, 'RoomID' => $room->RoomID];
            $report = Report::create($columns + ['RoomStatus' => 1]);
            $booking = new static($columns + ['ReportID' => $report->ReportID]);
            $booking->forceFill(BookingState::Requested->columns())->save();

            return $booking;
        });
    }

    /**
     * @param  array{BookingTitle: string, BookingAmount: int, BookingDetail: ?string}  $attrs
     *
     * @throws BookingRefused
     */
    public function reschedule(Room $room, BookingSlot $slot, array $attrs): static
    {
        return DB::transaction(function () use ($room, $slot, $attrs) {
            static::refuseUnlessFits($room, $slot, $attrs['BookingAmount'], $this->BookingID);

            $columns = $attrs + $slot->columns() + ['RoomID' => $room->RoomID];
            $this->fill($columns)->save();
            $this->report?->fill($columns)->save();

            return $this;
        });
    }

    private static function refuseUnlessFits(Room $room, BookingSlot $slot, int $amount, ?int $exceptId = null): void
    {
        Room::whereKey($room->RoomID)->lockForUpdate()->first();

        if ($amount > $room->RoomAmount) {
            throw BookingRefused::overCapacity($room);
        }
        if (static::conflicts($room->RoomID, $slot->start, $slot->end, $exceptId)->exists()) {
            throw $exceptId === null ? BookingRefused::overlap() : BookingRefused::pendingOverlap();
        }
    }

    public function approve(): static
    {
        return $this->transition(BookingState::Approved);
    }

    public function cancel(): static
    {
        return $this->transition(BookingState::Cancelled);
    }

    public function expire(): static
    {
        return $this->transition(BookingState::Expired);
    }

    private function transition(BookingState $to): static
    {
        $from = $this->state;
        if ($from === $to) {
            return $this;
        }
        if (! $from->canBecome($to)) {
            throw new LogicException("booking {$this->BookingID} is {$from->name} and cannot become {$to->name}");
        }

        return DB::transaction(function () use ($to) {
            $this->forceFill($to->columns())->save();
            if ($to->trashed() && ! $this->trashed()) {
                $this->delete();
            }

            return $this;
        });
    }

    public function confirmMeeting(): static
    {
        if ($this->BookingStatus !== 1) {
            $this->forceFill(['BookingStatus' => 1])->save();
        }

        return $this;
    }

    public function ownedBy(User $user): bool
    {
        return $this->id === $user->id;
    }

    public static function expireDue(): int
    {
        $due = static::inState(BookingState::Approved)->where('Booking_end', '<', now()->format('Y-m-d H:i:s'))->get();
        $due->each->expire();
        if ($due->isNotEmpty()) {
            Log::info("bookings:expire expired {$due->count()}");
        }

        return $due->count();
    }

    public function scopeConflicts(Builder $query, int $roomId, CarbonInterface $start, CarbonInterface $end, ?int $exceptId = null): Builder
    {
        return $query->where('RoomID', $roomId)
            ->where('VerifyStatus', '!=', 2)
            ->when($exceptId !== null, fn (Builder $q) => $q->where('BookingID', '!=', $exceptId))
            ->where('Booking_start', '<=', $end->format('Y-m-d H:i:s'))
            ->where('Booking_end', '>=', $start->format('Y-m-d H:i:s'));
    }

    public function scopeInState(Builder $query, BookingState $state): Builder
    {
        return match ($state) {
            BookingState::Requested => $query->where('VerifyStatus', 0),
            BookingState::Approved => $query->where('VerifyStatus', 1),
            BookingState::Expired => $query->onlyTrashed()->where('VerifyStatus', 1),
            BookingState::Cancelled => $query->withTrashed()->where('VerifyStatus', 2),
        };
    }

    protected function state(): Attribute
    {
        return Attribute::get(fn () => BookingState::of($this));
    }
}
