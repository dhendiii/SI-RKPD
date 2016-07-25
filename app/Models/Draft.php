<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Draft extends Eloquent{

  use SoftDeletes;

  protected $collection = 'draft';
  protected $hidden     = array('updated_at','created_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('kegiatan','draft_tipe','verifikasi','verifikasi_ket',
                                'hasilforum','hasilforum_ket','realisasi','realisasi_th',
                                'like','dislike','tag_id','location_id','user_id',
                                'information_id','feedback_id');

  public $timestamps    = true;

  public function information(){
      return $this->hasMany('App\Models\Information','_id', 'information_id');
  }
  public function feedback(){
      return $this->hasMany('App\Models\Feedback','_id', 'feedback_id');
  }
  public function location(){
      return $this->hasOne('App\Models\Location','_id', 'location_id');
  }
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'user_id');
  }
  public function tag(){
      return $this->hasMany('App\Models\Tag','_id', 'tag_id');
  }
}
