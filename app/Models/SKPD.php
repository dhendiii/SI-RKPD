<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class SKPD extends Eloquent{

  protected $collection = 'skpd';
  protected $hidden     = array('updated_at','created_at');
  protected $fillable   = array('nama', 'tags');

  public $timestamps    = true;

  //hasMany SKPD
  public function tag(){
      return $this->belongsTo('App\Models\Tag','tag_id');
  }
}
