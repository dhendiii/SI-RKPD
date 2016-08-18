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
                $result = Draft::take($limit)->skip($offset)->get();

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
        $kegiatan           = (isset($input['kegiatan']))           ? $input['kegiatan']            : null;
        $draft_tipe         = (isset($input['draft_tipe']))         ? $input['draft_tipe']          : null;

        // progress
        $verifikasi         = (isset($input['verifikasi']))         ? $input['verifikasi']          : null;
        $verifikasi_ket     = (isset($input['verifikasi_ket']))     ? $input['verifikasi_ket']      : null;
        $hasilforum         = (isset($input['hasilforum']))         ? $input['hasilforum']          : null;
        $hasilforum_ket     = (isset($input['hasilforum_ket']))     ? $input['hasilforum_ket']      : null;
        $realisasi          = (isset($input['realisasi']))          ? $input['realisasi']           : null;
        $realisasi_th       = (isset($input['realisasi_th']))       ? $input['realisasi_th']        : null;

        // lokasi
        $lokasi_cakupan     = (isset($input['lokasi_cakupan']))     ? $input['lokasi_cakupan']      : null;
        $lokasi_detail      = (isset($input['lokasi_detail']))      ? $input['lokasi_detail']       : null;
        $lokasi_kelurahan   = (isset($input['lokasi_kelurahan']))   ? $input['lokasi_kelurahan']    : null;
        $lokasi_kecamatan   = (isset($input['lokasi_kecamatan']))   ? $input['lokasi_kecamatan']    : null;

        // vote
        $like               = (isset($input['like']))               ? $input['like']                : 0;
        $dislike            = (isset($input['dislike']))            ? $input['dislike']             : 0;

        // foreign key
        $tags               = (isset($input['tags']))               ? $input['tags']                : null;
        $skpd               = (isset($input['skpd']))               ? $input['skpd']                : null;
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
        if (!isset($tags) || $tags == '') {
            $missingParams[] = "tags";
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
                        'kegiatan'          => $kegiatan,
                        'draft_tipe'        => $draft_tipe,
                        'verifikasi'        => $verifikasi,
                        'verifikasi_ket'    => $verifikasi_ket,
                        'hasilforum'        => $hasilforum,
                        'hasilforum_ket'    => $hasilforum_ket,
                        'realisasi'         => $realisasi,
                        'realisasi_th'      => $realisasi_th,
                        'lokasi_cakupan'    => $lokasi_cakupan,
                        'lokasi_detail'     => $lokasi_detail,
                        'lokasi_kelurahan'  => $lokasi_kelurahan,
                        'lokasi_kecamatan'  => $lokasi_kecamatan,
                        'like'              => $like,
                        'dislike'           => $dislike,
                        // 'tags'              => json_decode($tags, true),
                        'tags'              => $tags,
                        'user_id'           => $user_id,
                        // 'location_id'       => $location_id,
                    ));

                    $result['id']   = $draft->_id;
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
//                          ->with(array('information'))
//                          ->with(array('feedback'))
//                          ->with(array('user'))
//                          ->with(array('location'))
//                          ->with(array('tag'))
                          ->first();

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
        $kegiatan           = (isset($input['kegiatan']))       ? $input['kegiatan']                : null;
        $draft_tipe         = (isset($input['draft_tipe']))     ? $input['draft_tipe']              : null;

        // progress
        $verifikasi         = (isset($input['verifikasi']))     ? $input['verifikasi']              : null;
        $verifikasi_ket     = (isset($input['verifikasi_ket'])) ? $input['verifikasi_ket']          : null;
        $hasilforum         = (isset($input['hasilforum']))     ? $input['hasilforum']              : null;
        $hasilforum_ket     = (isset($input['hasilforum_ket'])) ? $input['hasilforum_ket']          : null;
        $realisasi          = (isset($input['realisasi']))      ? $input['realisasi']               : null;
        $realisasi_th       = (isset($input['realisasi_th']))   ? $input['realisasi_th']            : null;

        // vote
        $like               = (isset($input['like']))           ? $input['like']                    : null;
        $dislike            = (isset($input['dislike']))        ? $input['dislike']                 : null;

        // lokasi
        $lokasi_detail      = (isset($input['lokasi_detail']))      ? $input['lokasi_detail']       : null;
        $lokasi_kelurahan   = (isset($input['lokasi_kelurahan']))   ? $input['lokasi_kelurahan']    : null;
        $lokasi_kecamatan   = (isset($input['lokasi_kecamatan']))   ? $input['lokasi_kecamatan']    : null;

        // foreign key
        $tags               = (isset($input['tags']))           ? $input['tags']                    : null;
        $user_id            = (isset($input['user_id']))        ? $input['user_id']                 : null;

        $feedback_id        = (isset($input['feedback_id']))    ? $input['feedback_id']             : null;
        $information_id     = (isset($input['information_id'])) ? $input['information_id']          : null;


        if (!$isError) {
            try {
                $draft      = Draft::find($id);

                if ($draft) {
                    if (isset($kegiatan) && $kegiatan !== '') {
                        $editedParams[]       = "kegiatan";
                        $draft->push('archive_draft',array('kegiatan' => $draft->kegiatan, 'time' => \date("Y-m-d H:i:s")));
                        $draft->kegiatan      = $kegiatan;
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

                    if (isset($like) && $like !== '') {
                        $editedParams[]       = "like";
                        $draft->like     = $like;
                    }
                    if (isset($dislike) && $dislike !== '') {
                        $editedParams[]       = "dislike";
                        $draft->dislike     = $dislike;
                    }

                    if (isset($tags) && $tags !== '') {
                        $editedParams[]       = "tags";
                        $draft->push('tags', array('tags' => $tags));
                    }

                    if (isset($information_id) && $information_id !== '') {
                        $editedParams[]       = "information_id";
                        $draft->push('information_id', array('information_id' => $information_id));
                    }

                    if (isset($feedback_id) && $feedback_id !== '') {
                        $editedParams[]       = "feedback_id";
                        $draft->push('feedback_id', array('feedback_id' => $feedback_id));
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

}
