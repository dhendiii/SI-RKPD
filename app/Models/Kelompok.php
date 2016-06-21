<?php

namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Kelompok extends Eloquent {

	use SoftDeletes;

	protected $collection 	= 'kelompok';
	protected $hidden 		= array('created_at','updated_at');
    protected $dates 		= array('deleted_at');
    protected $fillable     = array('nama', 'priority');

    public $timestamps      = true;

}
