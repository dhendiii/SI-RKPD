<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Draft extends Eloquent{

  use SoftDeletes;

  protected $collection = 'draft';
  protected $hidden     = array('updated_at','created_at','id_location','id_feedback');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('summary','tipeDraft','status','prioritas');

  public $timestamps    = true;

  public function location(){
      return $this->hasOne('App\Models\Location','_id', 'id_location');
  }
  public function feedback(){
      return $this->hasOne('App\Models\Feedback','_id', 'id_feedback');
  }

}
