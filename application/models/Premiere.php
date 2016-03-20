<?php

namespace Premiefier\Models;

use Illuminate\Database\Eloquent\Model;

class Premiere extends Model
{
    public $timestamps  = false;
    protected $fillable = ['id', 'released_at', 'title', 'poster_url', 'details_url'];

    public function notifications()
    {
        return $this->hasMany('Premiefier\Models\Notification');
    }
}
