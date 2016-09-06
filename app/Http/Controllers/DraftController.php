<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Draft;
use App\Models\Feedback;
use App\Models\Information;
use App\Models\Location;
use App\Models\User;
use App\Models\Tag;

class DraftController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Mengambil semua data draft sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $draft      = Draft::with(array('user'))->take($limit)->skip($offset)->get();
                $feedback   = Feedback::raw(function($collection) {
                    return $collection->aggregate(array(
                        array('$match' => array('tipe' => array('$nin' => array('Komentar', 'Request informasi')))),
                        array('$group' => array('_id' => '$draft_id', 'total' => array('$sum' => 1)))
                    ));
                })->mapWithKeys(function ($o) {return [$o['_id'] => $o['total']];});

                $result     = $draft->each(function($val, $key) use ($feedback) {
                    if (isset($feedback[$val->_id])) { $val->feedback_count = $feedback[$val->_id]; } else { $val->feedback_count = 0; }
                });

                // $result = array(
                    // 'draft'     => $draft,
                    // 'feedback'  => $feedback,
                // );

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Menyimpan data draft baru sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();

        //inti
        $kegiatan               = (isset($input['kegiatan']))               ? $input['kegiatan']               : null;
        $draft_tipe             = (isset($input['draft_tipe']))             ? $input['draft_tipe']             : null;

        // progress
        $verifikasi             = (isset($input['verifikasi']))             ? $input['verifikasi']             : null;
        $verifikasi_ket         = (isset($input['verifikasi_ket']))         ? $input['verifikasi_ket']         : null;
        $hasilforum             = (isset($input['hasilforum']))             ? $input['hasilforum']             : null;
        $hasilforum_ket         = (isset($input['hasilforum_ket']))         ? $input['hasilforum_ket']         : null;
        $realisasi              = (isset($input['realisasi']))              ? $input['realisasi']              : null;
        $realisasi_th           = (isset($input['realisasi_th']))           ? $input['realisasi_th']           : null;

        // lokasi
        $lokasi_cakupan         = (isset($input['lokasi_cakupan']))         ? $input['lokasi_cakupan']         : null;
        $lokasi_detail          = (isset($input['lokasi_detail']))          ? $input['lokasi_detail']          : null;
        $lokasi_kelurahan       = (isset($input['lokasi_kelurahan']))       ? $input['lokasi_kelurahan']       : null;
        $lokasi_kecamatan       = (isset($input['lokasi_kecamatan']))       ? $input['lokasi_kecamatan']       : null;

        // vote
        $like                   = (isset($input['like']))                   ? $input['like']                   : 0;
        $dislike                = (isset($input['dislike']))                ? $input['dislike']                : 0;
        $like_users             = (isset($input['like_users']))             ? $input['like_users']             : null;
        $dislike_users          = (isset($input['dislike_users']))          ? $input['dislike_users']          : null;

        //tags and skpd
        $tags                   = (isset($input['tags']))                   ? $input['tags']                   : null;
        $skpd                   = (isset($input['skpd']))                   ? $input['skpd']                   : null;

        //informasi
        $ikp_konten             = (isset($input['ikp_konten']))             ? $input['ikp_konten']             : null;
        $ikp_jumlah             = (isset($input['ikp_jumlah']))             ? $input['ikp_jumlah']             : null;
        $ikp_unit               = (isset($input['ikp_unit']))               ? $input['ikp_unit']               : null;
        $ikp_verifikasi         = (isset($input['ikp_verifikasi']))         ? $input['ikp_verifikasi']         : null;
        $ikp_verket             = (isset($input['ikp_verket']))             ? $input['ikp_verket']             : null;

        $anggaran               = (isset($input['anggaran']))               ? $input['anggaran']               : null;
        $anggaran_verifikasi    = (isset($input['anggaran_verifikasi']))    ? $input['anggaran_verifikasi']    : null;
        $anggaran_verket        = (isset($input['anggaran_verket']))        ? $input['anggaran_verket']        : null;
        $sumberanggaran         = (isset($input['sumberanggaran']))         ? $input['sumberanggaran']         : null;

        $jeniskegiatan          = (isset($input['jeniskegiatan']))          ? $input['jeniskegiatan']          : null;
        $keterangan             = (isset($input['keterangan']))             ? $input['keterangan']             : null;

        $foto_verifikasi        = (isset($input['foto_verifikasi']))        ? $input['foto_verifikasi']        : null;
        $foto_verket            = (isset($input['foto_verket']))            ? $input['foto_verket']            : null;

        $proposal_verifikasi    = (isset($input['proposal_verifikasi']))    ? $input['proposal_verifikasi']    : null;
        $proposal_verket        = (isset($input['proposal_verket']))        ? $input['proposal_verket']        : null;

        $foto                   = ($request->hasFile('foto'))               ? $request->file('foto')           : null;
        $proposal               = ($request->hasFile('proposal'))           ? $request->file('proposal')       : null;

        // foreign key
        $user_id            = (isset($input['user_id']))            ? $input['user_id']             : null;


        if (!isset($kegiatan) || $kegiatan == '') {
            $missingParams[] = "kegiatan";
        }
        if (!isset($draft_tipe) || $draft_tipe == '') {
            $missingParams[] = "draft_tipe";
        }
        if (!isset($user_id) || $user_id == '') {
            $missingParams[] = "user_id";
        }
        if (!isset($lokasi_cakupan) || $lokasi_cakupan == '') {
            $missingParams[] = "lokasi_cakupan";
        }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $draft      = Draft::create(array(
                        'kegiatan'              => $kegiatan,
                        'draft_tipe'            => $draft_tipe,
                        'verifikasi'            => $verifikasi,
                        'verifikasi_ket'        => $verifikasi_ket,
                        'hasilforum'            => $hasilforum,
                        'hasilforum_ket'        => $hasilforum_ket,
                        'realisasi'             => $realisasi,
                        'realisasi_th'          => $realisasi_th,
                        'lokasi_cakupan'        => $lokasi_cakupan,
                        'lokasi_detail'         => $lokasi_detail,
                        'lokasi_kelurahan'      => $lokasi_kelurahan,
                        'lokasi_kecamatan'      => $lokasi_kecamatan,
                        'like'                  => $like,
                        'dislike'               => $dislike,
                        'like_users'            => array($like_users),
                        'dislike_users'         => array($dislike_users),
                        'skpd'                  => $skpd,
                        'tags'                  => json_decode($tags, true),
                        'ikp_konten'            => $ikp_konten,
                        'ikp_jumlah'            => $ikp_jumlah,
                        'ikp_unit'              => $ikp_unit,
                        'ikp_verifikasi'        => $ikp_verifikasi,
                        'ikp_verket'            => $ikp_verket,
                        'anggaran'              => $anggaran,
                        'anggaran_verifikasi'   => $anggaran_verifikasi,
                        'anggaran_verket'       => $anggaran_verket,
                        'sumberanggaran'        => $sumberanggaran,
                        'jeniskegiatan'         => $jeniskegiatan,
                        'keterangan'            => $keterangan,
                        'foto_verifikasi'       => $foto_verifikasi,
                        'foto_verket'           => $foto_verket,
                        'proposal_verifikasi'   => $proposal_verifikasi,
                        'proposal_verket'       => $proposal_verket,
                        'foto'                  => null,
                        'proposal'              => null,
                        'user_id'               => $user_id,
                    ));

                    if ($foto) {
                        $foto_list = [];
                        foreach ($foto as $key => $value) {
                            # code...
                            $extension = $value->getClientOriginalExtension();
                            if (in_array($extension, array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG',))){
                                $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                                $uploadedDest   = 'images/'.$draft->_id;
                                $uploadedName   = $random_name.'.'.$extension;

                                $value->move($uploadedDest, $uploadedName);

                                $foto_list[]   = $uploadedDest.'/'.$uploadedName;
                            }
                        }
                        $draft->foto   = $foto_list;
                        $draft->save();
                    }

                    if ($proposal) {
                        $extension  = $proposal->getClientOriginalExtension();
                        if (in_array($extension,  array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                            $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                            $uploadedDest   = 'docs/'.$draft->_id;
                            $uploadedName   = $random_name.'.'.$extension;

                            $proposal->move($uploadedDest, $uploadedName);

                            $draft->proposal   = $uploadedDest.'/'.$uploadedName;
                            $draft->save();
                        }
                    }

                    // $result['id']   = $draft->_id;
                    $result   = array(
                        '_id'                 => $draft->_id,
                        'draft_tipe'          => $draft->draft_tipe,
                    );

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Mengambil data draft dengan id $id sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $result = Draft::where('_id', $id)
                            ->with(array('user'))
                        //  ->with(array('information'))
                        //  ->with(array('feedback'))
                            ->first();

                        //   $result = Draft::with(array('user'))->take($limit)->skip($offset)->get();

                        $result->feedback   = $result->getFeedback();

                if (!$result) {
                    throw new \Exception("Draft dengan id $id tidak ditemukan.");
                } else {

                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Menyunting data draft sukses.";
        $isError            = FALSE;
        $editedParams       = null;

        $input              = $request->all();

        //inti
        $kegiatan                   = (isset($input['kegiatan']))               ? $input['kegiatan']                : null;
        $draft_tipe                 = (isset($input['draft_tipe']))             ? $input['draft_tipe']              : null;

        // progress
        $verifikasi                 = (isset($input['verifikasi']))             ? $input['verifikasi']              : null;
        $verifikasi_ket             = (isset($input['verifikasi_ket']))         ? $input['verifikasi_ket']          : null;
        $hasilforum                 = (isset($input['hasilforum']))             ? $input['hasilforum']              : null;
        $hasilforum_ket             = (isset($input['hasilforum_ket']))         ? $input['hasilforum_ket']          : null;
        $realisasi                  = (isset($input['realisasi']))              ? $input['realisasi']               : null;
        $realisasi_th               = (isset($input['realisasi_th']))           ? $input['realisasi_th']            : null;

        // vote
        $like                       = (isset($input['like']))                   ? $input['like']                    : null;
        $dislike                    = (isset($input['dislike']))                ? $input['dislike']                 : null;
        $like_users                 = (isset($input['like_users']))             ? $input['like_users']              : null;
        $dislike_users              = (isset($input['dislike_users']))          ? $input['dislike_users']           : null;

        // lokasi
        $lokasi_cakupan             = (isset($input['lokasi_cakupan']))         ? $input['lokasi_cakupan']          : null;
        $lokasi_detail              = (isset($input['lokasi_detail']))          ? $input['lokasi_detail']           : null;
        $lokasi_kelurahan           = (isset($input['lokasi_kelurahan']))       ? $input['lokasi_kelurahan']        : null;
        $lokasi_kecamatan           = (isset($input['lokasi_kecamatan']))       ? $input['lokasi_kecamatan']        : null;

        // foreign key
        $tags                       = (isset($input['tags']))                   ? $input['tags']                    : null;
        $skpd                       = (isset($input['skpd']))                   ? $input['skpd']                    : null;

        //informasi
        $ikp_konten                 = (isset($input['ikp_konten']))             ? $input['ikp_konten']              : null;
        $ikp_jumlah                 = (isset($input['ikp_jumlah']))             ? $input['ikp_jumlah']              : null;
        $ikp_unit                   = (isset($input['ikp_unit']))               ? $input['ikp_unit']                : null;
        $ikp_verifikasi             = (isset($input['ikp_verifikasi']))         ? $input['ikp_verifikasi']          : null;
        $ikp_verket                 = (isset($input['ikp_verket']))             ? $input['ikp_verket']              : null;

        $anggaran                   = (isset($input['anggaran']))               ? $input['anggaran']                : null;
        $anggaran_verifikasi        = (isset($input['anggaran_verifikasi']))    ? $input['anggaran_verifikasi']     : null;
        $anggaran_verket            = (isset($input['anggaran_verket']))        ? $input['anggaran_verket']         : null;
        $sumberanggaran             = (isset($input['sumberanggaran']))         ? $input['sumberanggaran']          : null;

        $jeniskegiatan              = (isset($input['jeniskegiatan']))          ? $input['jeniskegiatan']           : null;
        $keterangan                 = (isset($input['keterangan']))             ? $input['keterangan']              : null;

        $foto_verifikasi            = (isset($input['foto_verifikasi']))        ? $input['foto_verifikasi']         : null;
        $foto_verket                = (isset($input['foto_verket']))            ? $input['foto_verket']             : null;

        $proposal_verifikasi        = (isset($input['proposal_verifikasi']))    ? $input['proposal_verifikasi']     : null;
        $proposal_verket            = (isset($input['proposal_verket']))        ? $input['proposal_verket']         : null;

        $foto                       = ($request->hasFile('foto'))               ? $request->file('foto')            : null;
        $proposal                   = ($request->hasFile('proposal'))           ? $request->file('proposal')        : null;

        // $user_id                    = (isset($input['user_id']))        ? $input['user_id']                 : null;
        // $feedback_id                = (isset($input['feedback_id']))    ? $input['feedback_id']             : null;
        // $information_id             = (isset($input['information_id'])) ? $input['information_id']          : null;


        if (!$isError) {
            try {
                $draft      = Draft::find($id);

                if ($draft) {
                    if (isset($kegiatan) && $kegiatan !== '') {
                        $editedParams[]       = "kegiatan";
                        $draft->push('archive_draft',array('kegiatan' => $draft->kegiatan, 'time' => \date("Y-m-d H:i:s")));
                        $draft->kegiatan      = $kegiatan;
                    }
                    if (isset($draft_tipe) && $draft_tipe !== '') {
                        $editedParams[]       = "draft_tipe";
                        $draft->draft_tipe     = $draft_tipe;
                    }

                    if (isset($verifikasi) && $verifikasi !== '') {
                        $editedParams[]       = "verifikasi";
                        $draft->verifikasi     = $verifikasi;
                    }
                    if (isset($verifikasi_ket) && $verifikasi_ket !== '') {
                        $editedParams[]       = "verifikasi_ket";
                        $draft->verifikasi_ket     = $verifikasi_ket;
                    }
                    if (isset($hasilforum) && $hasilforum !== '') {
                        $editedParams[]       = "hasilforum";
                        $draft->hasilforum     = $hasilforum;
                    }
                    if (isset($hasilforum_ket) && $hasilforum_ket !== '') {
                        $editedParams[]       = "hasilforum_ket";
                        $draft->hasilforum_ket     = $hasilforum_ket;
                    }
                    if (isset($realisasi) && $realisasi !== '') {
                        $editedParams[]       = "realisasi";
                        $draft->realisasi     = $realisasi;
                    }
                    if (isset($realisasi_th) && $realisasi_th !== '') {
                        $editedParams[]       = "realisasi_th";
                        $draft->realisasi_th     = $realisasi_th;
                    }

                    if (isset($like_users) && $like_users !== '') {
                        if (!in_array($like_users, $draft->like_users)) {
                            $editedParams[]       = "like";

                            if (in_array($like_users, $draft->dislike_users)) {
                                $draft->decrement("dislike");
                                $draft->pull('dislike_users', $like_users);
                            }

                            $draft->increment("like");
                            $draft->push('like_users', $like_users);

                            $result = array(
                                'like'      => $draft->like,
                                'dislike'   => $draft->dislike,
                            );
                        }
                    }
                    if (isset($dislike_users) && $dislike_users !== '') {
                        if (!in_array($dislike_users, $draft->dislike_users)) {
                            $editedParams[]       = "dislike";

                            if (in_array($dislike_users, $draft->like_users)) {
                                $draft->decrement("like");
                                $draft->pull('like_users', $dislike_users);
                            }

                            $draft->increment("dislike");
                            $draft->push('dislike_users', $dislike_users);

                            $result = array(
                                'like'      => $draft->like,
                                'dislike'   => $draft->dislike,
                            );
                        }
                    }

                    if (isset($lokasi_cakupan) && $lokasi_cakupan !== '') {
                        $editedParams[]       = "lokasi_cakupan";
                        $draft->lokasi_cakupan     = $lokasi_cakupan;
                    }
                    if (isset($lokasi_detail) && $lokasi_detail !== '') {
                        $editedParams[]       = "lokasi_detail";
                        $draft->lokasi_detail     = $lokasi_detail;
                    }
                    if (isset($lokasi_kelurahan) && $lokasi_kelurahan !== '') {
                        $editedParams[]       = "lokasi_kelurahan";
                        $draft->lokasi_kelurahan     = $lokasi_kelurahan;
                    }
                    if (isset($lokasi_kecamatan) && $lokasi_kecamatan !== '') {
                        $editedParams[]       = "lokasi_kecamatan";
                        $draft->lokasi_kecamatan     = $lokasi_kecamatan;
                    }

                    if (isset($tags) && $tags !== '') {
                        $editedParams[]       = "tags";
                        // $draft->push('tags', array('tags' => $tags));
                        $draft->tags          = json_decode($tags, true);
                    }

                    if (isset($lokasi_detail) && $lokasi_detail !== '') {
                        $editedParams[]       = "lokasi_detail";
                        $draft->lokasi_detail   = $lokasi_detail;
                    }

                    if (isset($lokasi_kelurahan) && $lokasi_kelurahan !== '') {
                        $editedParams[]       = "lokasi_kelurahan";
                        $draft->lokasi_kelurahan   = $lokasi_kelurahan;
                    }

                    if (isset($lokasi_kecamatan) && $lokasi_kecamatan !== '') {
                        $editedParams[]       = "lokasi_kecamatan";
                        $draft->lokasi_kecamatan   = $lokasi_kecamatan;
                    }

                    // if (isset($information_id) && $information_id !== '') {
                    //     $editedParams[]       = "information_id";
                    //     $draft->push('information_id', array('information_id' => $information_id));
                    // }
                    //
                    // if (isset($feedback_id) && $feedback_id !== '') {
                    //     $editedParams[]       = "feedback_id";
                    //     $draft->push('feedback_id', array('feedback_id' => $feedback_id));
                    // }

                    if (isset($ikp_konten) && $ikp_konten !== '') {
                        $editedParams[]       = "ikp_konten";
                        $draft->ikp_konten   = $ikp_konten;
                    }
                    if (isset($ikp_jumlah) && $ikp_jumlah !== '') {
                        $editedParams[]       = "ikp_jumlah";
                        $draft->ikp_jumlah   = $ikp_jumlah;
                    }
                    if (isset($ikp_unit) && $ikp_unit !== '') {
                        $editedParams[]       = "ikp_unit";
                        $draft->ikp_unit   = $ikp_unit;
                    }
                    if (isset($ikp_verifikasi) && $ikp_verifikasi !== '') {
                        $editedParams[]       = "ikp_verifikasi";
                        $draft->ikp_verifikasi   = $ikp_verifikasi;
                    }
                    if (isset($ikp_verket) && $ikp_verket !== '') {
                        $editedParams[]       = "ikp_verket";
                        $draft->ikp_verket   = $ikp_verket;
                    }

                    if (isset($anggaran) && $anggaran !== '') {
                        $editedParams[]       = "anggaran";
                        $draft->anggaran   = $anggaran;
                    }
                    if (isset($anggaran_verifikasi) && $anggaran_verifikasi !== '') {
                        $editedParams[]       = "anggaran_verifikasi";
                        $draft->anggaran_verifikasi   = $anggaran_verifikasi;
                    }
                    if (isset($anggaran_verket) && $anggaran_verket !== '') {
                        $editedParams[]       = "anggaran_verket";
                        $draft->anggaran_verket   = $anggaran_verket;
                    }

                    if (isset($sumberanggaran) && $sumberanggaran !== '') {
                        $editedParams[]       = "sumberanggaran";
                        $draft->sumberanggaran   = $sumberanggaran;
                    }
                    if (isset($jeniskegiatan) && $jeniskegiatan !== '') {
                        $editedParams[]       = "jeniskegiatan";
                        $draft->jeniskegiatan   = $jeniskegiatan;
                    }
                    if (isset($keterangan) && $keterangan !== '') {
                        $editedParams[]       = "keterangan";
                        $draft->keterangan   = $keterangan;
                    }

                    if (isset($foto_verifikasi) && $foto_verifikasi !== '') {
                        $editedParams[]       = "foto_verifikasi";
                        $draft->foto_verifikasi   = $foto_verifikasi;
                    }
                    if (isset($foto_verket) && $foto_verket !== '') {
                        $editedParams[]       = "foto_verket";
                        $draft->foto_verket   = $foto_verket;
                    }

                    if (isset($proposal_verifikasi) && $proposal_verifikasi !== '') {
                        $editedParams[]       = "proposal_verifikasi";
                        $draft->proposal_verifikasi   = $proposal_verifikasi;
                    }
                    if (isset($proposal_verket) && $proposal_verket !== '') {
                        $editedParams[]       = "proposal_verket";
                        $draft->proposal_verket   = $proposal_verket;
                    }

                    if ($foto) {
                        $extension  = $foto->getClientOriginalExtension();
                        if (in_array($extension, array('jpg', 'jpeg','png', 'JPG', 'JPEG', 'PNG',))) {
                            $editedParams[] = "foto";
                            if (file_exists($draft->foto)) { unlink($draft->foto); }

                            $uploadedDest   = 'images/'.$id;
                            $uploadedName   = 'foto.'.$extension;

                            $foto->move($uploadedDest, $uploadedName);
                            $draft->foto   = $uploadedDest.'/'.$uploadedName;
                        }
                    }
                    if ($proposal) {
                        $extension  = $proposal->getClientOriginalExtension();
                        if (in_array($extension, array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                            $editedParams[] = "proposal";
                            if (file_exists($draft->proposal)) { unlink($draft->proposal); }

                            $uploadedDest   = 'images/'.$id;
                            $uploadedName   = 'proposal.'.$extension;

                            $proposal->move($uploadedDest, $uploadedName);
                            $draft->proposal   = $uploadedDest.'/'.$uploadedName;
                        }
                    }

                    if (isset($editedParams)) {
                        $draft->save();
                        $message    = $message." Data yang berubah : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." Tidak ada data yang berubah.";
                    }
                } else {
                    throw new \Exception("Draft dengan id $id tidak ditemukan.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Menghapus data draft dengan $id sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $draft      = Draft::find($id);

                if ($draft) {
                    $draft->delete();
                } else {
                    throw new \Exception("Draft dengan id $id tidak ditemukan.");
                }
            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    public function tags() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "mengambil semua tags sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                // {$match : {tags : {$ne : null}}}, { $unwind: "$tags" }, {$group : {_id : '$tags'}}
                $tags       = Draft::raw(function($collection) {
                    return $collection->aggregate(array(
                        array('$match'  => array('tags' => array('$ne' => 'null'))),
                        array('$unwind' => '$tags'),
                        array('$group'  => array('_id'  => '$tags')),
                        // array('$group'  => array('_id'  => 1, 'result' => array('$addToSet' => '$tags')))
                    ));
                });

                $result     = $tags;

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    public function whereIn() {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Belajar Where In.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $user_id    = "57c14e2844a5793ad06c7dbe";

                // $result     = Draft::whereIn('like_users', array($user_id))->get(array('_id'));
                $result     = Draft::where('user_id', $user_id)->get();
                $result->feed = $result->getSubmitInfo();

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }

    public function uploadFiles(Request $request) {
        $returnData         = array();
        $response           = "OK";
        $statusCode         = 200;
        $result             = null;
        $message            = "Test menyimpan foto baru sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();

        $foto                   = ($request->hasFile('foto'))               ? $request->file('foto')           : null;
        $proposal               = ($request->hasFile('proposal'))           ? $request->file('proposal')       : null;

        if (!isset($foto) || $foto == '') {
            $missingParams[] = "foto";
        }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $testfoto      = Draft::create(array(
                        'foto'                  => null,
                        'proposal'              => null,
                    ));

                    if ($foto) {
                        $foto_list = [];
                        foreach ($foto as $key => $value) {
                            # code...
                            $extension = $value->getClientOriginalExtension();
                            if (in_array($extension, array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG',))){
                                $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                                $uploadedDest   = 'images/test/';
                                $uploadedName   = $random_name.'.'.$extension;

                                $value->move($uploadedDest, $uploadedName);

                                $foto_list[]   = $uploadedDest.'/'.$uploadedName;
                            }
                        }
                        $testfoto->foto   = $foto_list;
                        $testfoto->save();
                    }

                    if ($proposal) {
                        $extension  = $proposal->getClientOriginalExtension();
                        if (in_array($extension,  array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                            $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                            $uploadedDest   = 'docs/test'.$testfoto->_id;
                            $uploadedName   = $random_name.'.'.$extension;

                            $proposal->move($uploadedDest, $uploadedName);

                            $testfoto->proposal   = $uploadedDest.'/'.$uploadedName;
                            $testfoto->save();
                        }
                    }

                    // $result['id']   = $testfoto->_id;
                    $result   = array(
                        '_id'                 => $testfoto->_id,
                    );

            } catch (\Exception $e) {
                $response   = "FAILED";
                $statusCode = 400;
                $message    = $e->getMessage()." on line: " . $e->getLine();
            }
        }

        $returnData = array(
            'response'      => $response,
            'status_code'   => $statusCode,
            'message'       => $message,
            'result'        => $result
        );

        return  response()->json($returnData, $statusCode)->header('access-control-allow-origin', '*');
    }


}
