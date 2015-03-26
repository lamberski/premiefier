<?php

namespace Premiefier\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

  public $timestamps = false;
  protected $fillable = ['user_id', 'premiere_id'];

  public function user()
  {
    return $this->belongsTo('Premiefier\Models\User');
  }

  public function premiere()
  {
    return $this->belongsTo('Premiefier\Models\Premiere');
  }

}
