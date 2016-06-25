<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Tag extends Eloquent{

  protected $collection = 'tag';
  protected $hidden     = array('updated_at','created_at');
  protected $fillable   = array('nama','skpd_id');

  public $timestamps    = true;

  public function getSkpd(){
      return SKPD::whereIn('_id', $this->skpd_id)->get();
  }

}
