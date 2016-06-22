<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 *
 */
class User extends Eloquent{

  use SoftDeletes;

  protected $collection = 'user';
  protected $hidden     = array('created_at','updated_at','password');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('namaDepan','namaBelakang','noKTP','email','password','tipeUser','poin','lencana');

  public $timestamps    = true;
}
