<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 *
 */
class Location extends Eloquent{

  use SoftDeletes;

  protected $collection = 'location';
  protected $hidden     = array('created_at','updated_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('detail','dusun','kelurahan','kecamatan');

  public $timestamps    = true;
}
