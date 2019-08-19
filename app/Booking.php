<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['room_id', 'date_start', 'date_end', 'name', 'email', 'phone_number', 'total_nights', 'total_price', 'user_id'];

    protected $with = ['room'];

    /**
     * Get the rooom that has been booked.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function room()
    {
        return $this->belongsTo(RoomManager::class);
    }

}
