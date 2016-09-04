<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class User extends Eloquent{

  use SoftDeletes;

  protected $collection = 'user';
  protected $hidden     = array('created_at','updated_at','password');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('nama_depan','nama_belakang','no_ktp','email','no_hp',
                                'password','user_tipe','no_ktp','skpd','skpd_role',
                                'lokasi_detail','lokasi_kelurahan', 'lokasi_kecamatan',
                                'user_poin','lencana');

  public $timestamps    = true;

  public function inforamtion(){
      return $this->hasMany('App\Models\Information', '_id');
  }

  public function draft(){
      return $this->hasMany('App\Models\Draft', '_id');
  }

  public function feedback(){
      return $this->hasMany('App\Models\Feedback', '_id');
  }


  public function getDraft(){
      return Draft::whereIn('_id', $this->draft_id)->get();
  }


  // public function getInformation(){
  //     return Information::whereIn('_id', $this->information_id)->get();
  // }
  // public function getFeedback(){
  //     return Feedback::whereIn('_id', $this->feedback_id)->get();
  // }


  //hasMany draft
  //hasMany information
  //hasMany feedback
}
