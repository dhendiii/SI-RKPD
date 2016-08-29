<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Feedback extends Eloquent{

  use SoftDeletes;

  protected $collection = 'feedback';
  protected $hidden     = array('updated_at','created_at');
  protected $dates      = array('deleted_at');
  // protected $fillable   = array('message','feed_tipe','status','draft_id',
  //                           'user_id');
  protected $fillable   = array('konten', 'tipe', 'status', 'status_ket',
                                'like', 'dislike', 'draft_id', 'user_id',
                                );


  public $timestamps    = true;


  //hasOne user
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'user_id');
  }

  //hasOne Draft
  public function draft(){
      return $this->hasOne('App\Models\Draft','_id', 'draft_id');
  }
}
