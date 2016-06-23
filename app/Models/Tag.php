<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Tag extends Eloquent{


  protected $collection = 'tag';
  protected $hidden     = array('updated_at','created_at');
  protected $fillable   = array('nama','id_skpd');

  public $timestamps    = true;

  public function skpd(){
      return $this->hasMany('App\Models\SKPD','_id', 'id_skpd');
  }
}
