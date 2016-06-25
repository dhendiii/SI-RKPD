<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Information extends Eloquent{

  use SoftDeletes;

  protected $collection = 'information';
  protected $hidden     = array('created_at','updated_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('message','informasi_tipe','status','prioritas',
                            'draft_id','user_id');

  public $timestamps    = true;

  //hasOne user
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }

  //hasOne Draft
  public function draft(){
      return $this->hasOne('App\Models\Draft','_id', 'draft_id');
  }

}
