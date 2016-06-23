<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Feedback extends Eloquent{

  use SoftDeletes;

  protected $collection = 'feedback';
  protected $hidden     = array('updated_at','created_at','id_location');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('message','tipeFeed','status');

  public $timestamps    = true;

  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }
}
