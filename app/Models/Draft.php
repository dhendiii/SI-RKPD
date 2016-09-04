<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Feedback;

class Draft extends Eloquent{

  use SoftDeletes;

  protected $collection = 'draft';
  protected $hidden     = array('updated_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('kegiatan','draft_tipe','verifikasi','verifikasi_ket',
                                'hasilforum','hasilforum_ket','realisasi','realisasi_th',
                                'lokasi_cakupan','lokasi_detail','lokasi_kelurahan','lokasi_kecamatan',
                                'like','dislike','like_users', 'dislike_users', 'tags', 'skpd','user_id',
                                'ikp_konten', 'ikp_jumlah', 'ikp_unit', 'ikp_verifikasi', 'ikp_verket',
                                'anggaran', 'anggaran_verifikasi', 'anggaran_verket',
                                'sumberanggaran', 'jeniskegiatan', 'keterangan',
                                'foto', 'foto_verifikasi', 'foto_verket',
                                'proposal', 'proposal_verifikasi', 'proposal_verket',
                                'information_id','feedback_id');

  public $timestamps    = true;

  public function information(){
      return $this->hasOne('App\Models\Information','_id', 'information_id');
  }
  public function feedback(){
      return $this->hasMany('App\Models\Feedback','_id', 'feedback_id');
  }
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'user_id');
  }

  public function getFeedback(){
      return Feedback::where('draft_id', $this->_id)->with(array('user'))->get();
    //   $result     = Draft::where('user_id', $user_id)->get();
  }

  public function getFeedInfo(){
      return Feedback::where('draft_id', $this->_id)->where('tipe', 'informasi')->with(array('user'))->get();
  }

  // public function tag(){
  //     return $this->hasMany('App\Models\Tag','_id', 'tag_id');
  // }

  // public function information(){
  //     return $this->hasMany('App\Models\Information', '_id');
  // }
  // public function feedback(){
  //     return $this->hasMany('App\Models\Feedback');
  // }
  //
  // public function user(){
  //     return $this->belongsTo('App\Models\User','_id', 'user_id');
  // }
}
