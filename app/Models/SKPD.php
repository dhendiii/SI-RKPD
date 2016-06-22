<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class SKPD extends Eloquent{

  protected $collection = 'skpd';
  protected $hidden     = array('updated_at','created_at');
  protected $fillable   = array('namaPanjang','namaSingkat');

  public $timestamps    = true;
}
