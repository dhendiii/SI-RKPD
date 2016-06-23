<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Information extends Eloquent{

  use SoftDeletes;

  protected $collection = 'information';
  protected $hidden     = array('created_at','updated_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('tipeInformasi','message','status');

  public $timestamps    = true;

  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }
}
