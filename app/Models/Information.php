<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Information extends Eloquent{

  use SoftDeletes;

  protected $collection = 'information';
  protected $hidden     = array('created_at','updated_at');
  protected $dates      = array('deleted_at');
  protected $fillable   = array('konten','informasi_tipe','jumlah','satuan',
                                'like','dislike','verifikasi_ket','verifikasi_ket',
                                'draft_id','user_id');

  public $timestamps    = true;

  //hasOne user
  public function user(){
      return $this->hasOne('App\Models\User','_id', 'id_user');
  }

  //hasOne Draft
  public function draft(){
      return $this->hasOne('App\Models\Draft','_id', 'draft_id');
  }

}

$informasi_tipe     = (isset($input['informasi_tipe']))   ? $input['informasi_tipe']  : null;
$konten             = (isset($input['konten']))           ? $input['konten']          : null;

$jumlah             = (isset($input['jumlah']))           ? $input['jumlah']          : 0;
$satuan             = (isset($input['satuan']))           ? $input['satuan']          : null;

$like               = (isset($input['like']))             ? $input['like']            : 0;
$dislike            = (isset($input['dislike']))          ? $input['dislike']         : 0;

$verifikasi         = (isset($input['verifikasi']))       ? $input['verifikasi']      : null;
$verifikasi_ket     = (isset($input['verifikasi_ket']))   ? $input['verifikasi_ket']  : null;

$user_id            = (isset($input['user_id']))          ? $input['user_id']         : null;
$draft_id           = (isset($input['draft_id']))         ? $input['draft_id']        : null;
