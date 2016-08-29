<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Information extends Eloquent{

  use SoftDeletes;

  protected $collection = 'information';
  protected $hidden     = array('created_at','updated_at');
  protected $dates      = array('deleted_at');
  // protected $fillable   = array('konten','informasi_tipe','jumlah','satuan',
  //                               'like','dislike','verifikasi_ket','verifikasi_ket',
  //                               'draft_id','user_id');\
  protected $fillable   = array('ikp_konten', 'ikp_jumlah', 'ikp_unit', 'ikp_verifikasi', 'ikp_verket',
                                'anggaran', 'anggaran_verifikasi', 'anggaran_verket',
                                'sumberanggaran', 'jeniskegiatan', 'keterangan',
                                'foto', 'foto_verifikasi', 'foto_verket',
                                'proposal', 'proposal_verifikasi', 'proposal_verket',
                                'draft_id', 'user_id',
                            );

  public $timestamps    = true;

  //hasOne user
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }

  //hasOne Draft
  public function draft(){
      return $this->belongsTo('App\Models\Draft','_id', 'draft_id');
  }

}
