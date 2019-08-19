<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomManager extends Model
{
    protected $fillable = ['name', 'hotel_id', 'room_type_id', 'image'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['room_type'];

    /**
     * Get the hotel that owns the room.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function hotel()
    {
        return $this->belongsTo(HotelDetails::class);
    }

    /**
     * Get the room_type that owns the room.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get all bookings for this room.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function booking()
    {
        return $this->hasMany('App\Booking', 'room_id');
    }
}
