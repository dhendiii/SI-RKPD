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

  public function information(){
      return $this->hasMany('App\Models\Information','_id', 'id_information');
  }
  public function feedback(){
      return $this->hasMany('App\Models\Feedback','_id', 'id_feedback');
  }

  public function location(){
      return $this->hasOne('App\Models\Location','_id', 'id_location');
  }
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }
  public function tag(){
      return $this->hasMany('App\Models\Tag','_id', 'id_tag');
  }
}
