<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = ['price', 'room_type_id'];

    /**
     * Get the room type of this room price.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }

}
