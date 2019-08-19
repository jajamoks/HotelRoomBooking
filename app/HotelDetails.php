<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelDetails extends Model
{
    protected $fillable = ['name', 'address', 'city', 'state', 'country', 'zip_code', 'phone_number', 'email', 'image'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['rooms'];

    /**
     * Get all rooms that belong to this hotel.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function rooms()
    {
        return $this->hasMany('App\RoomManager', 'hotel_id');
    }
}
