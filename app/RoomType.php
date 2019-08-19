<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = ['type'];

    protected $with = ['price'];

    /**
     * Get all rooms that belong to this room type.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function rooms()
    {
        return $this->hasMany(RoomManager::class);
    }

    /**
     * Get all pricing for this room type.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function price()
    {
        return $this->hasMany(PriceList::class);
    }
}
