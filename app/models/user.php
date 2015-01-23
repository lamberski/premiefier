<?php

namespace Premiefier\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
  public $timestamps = false;
  protected $fillable = ['email'];

  public function notifications() {
    return $this->hasMany('Premiefier\Models\Notification');
  }
}
