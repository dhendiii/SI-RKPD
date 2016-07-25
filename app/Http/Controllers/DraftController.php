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
        $kegiatan           = (isset($input['kegiatan']))       ? $input['kegiatan']        : null;
        $draft_tipe         = (isset($input['draft_tipe']))     ? $input['draft_tipe']      : null;

        // progress
        $verifikasi         = (isset($input['verifikasi']))     ? $input['verifikasi']      : null;
        $verifikasi_ket     = (isset($input['verifikasi_ket'])) ? $input['verifikasi_ket']  : null;
        $hasilforum         = (isset($input['hasilforum']))     ? $input['hasilforum']      : null;
        $hasilforum_ket     = (isset($input['hasilforum_ket'])) ? $input['hasilforum_ket']  : null;
        $realisasi          = (isset($input['realisasi']))      ? $input['realisasi']       : FALSE;
        $realisasi_th       = (isset($input['realisasi_th']))   ? $input['realisasi_th']    : null;

        // vote
        $like               = (isset($input['like']))           ? $input['like']            : 0;
        $dislike            = (isset($input['dislike']))        ? $input['dislike']         : 0;

        // foreign key
        $tag_id             = (isset($input['tag_id']))         ? $input['tag_id']          : null;
        $user_id            = (isset($input['user_id']))        ? $input['user_id']         : null;
        $location_id        = (isset($input['location_id']))    ? $input['location_id']     : null;


        if (!isset($kegiatan) || $kegiatan == '') {
            $missingParams[] = "kegiatan";
        }
        if (!isset($draft_tipe) || $draft_tipe == '') {
            $missingParams[] = "draft_tipe";
        }
        if (!isset($user_id) || $user_id == '') {
            $missingParams[] = "user_id";
        }
        if (!isset($tag_id) || $tag_id == '') {
            $missingParams[] = "tag_id";
        }
        if (!isset($location_id) || $location_id == '') {
            $missingParams[] = "location_id";
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
                        'like'              => $like,
                        'dislike'           => $dislike,
                        'tag_id'            => json_decode($tag_id, true),
                        'user_id'           => $user_id,
                        'location_id'       => $location_id,
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
        $kegiatan           = (isset($input['kegiatan']))       ? $input['kegiatan']        : null;
        $draft_tipe         = (isset($input['draft_tipe']))     ? $input['draft_tipe']      : null;

        // progress
        $verifikasi         = (isset($input['verifikasi']))     ? $input['verifikasi']      : null;
        $verifikasi_ket     = (isset($input['verifikasi_ket'])) ? $input['verifikasi_ket']  : null;
        $hasilforum         = (isset($input['hasilforum']))     ? $input['hasilforum']      : null;
        $hasilforum_ket     = (isset($input['hasilforum_ket'])) ? $input['hasilforum_ket']  : null;
        $realisasi          = (isset($input['realisasi']))      ? $input['realisasi']       : FALSE;
        $realisasi_th       = (isset($input['realisasi_th']))   ? $input['realisasi_th']    : null;

        // vote
        $like               = (isset($input['like']))           ? $input['like']            : null;
        $dislike            = (isset($input['dislike']))        ? $input['dislike']         : null;

        // foreign key
        $tag_id             = (isset($input['tag_id']))         ? $input['tag_id']          : null;
        $user_id            = (isset($input['user_id']))        ? $input['user_id']         : null;
        $location_id        = (isset($input['location_id']))    ? $input['location_id']     : null;

        $feedback_id        = (isset($input['feedback_id']))    ? $input['feedback_id']     : null;
        $information_id     = (isset($input['information_id'])) ? $input['information_id']  : null;


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

                    if (isset($tag_id) && $tag_id !== '') {
                        $editedParams[]       = "tag_id";
                        $draft->push('tag_id', array('tag_id' => $tag_id));
                    }

                    if (isset($information_id) && $information_id !== '') {
                        $editedParams[]       = "information_id";
                        $draft->push('information_id', array('information_id' => $information_id));
                    }

                    if (isset($feedback_id) && $feedback_id !== '') {
                        $editedParams[]       = "feedback_id";
                        $draft->push('feedback_id', array('feedback_id' => $feedback_id));
                    }

                    if (isset($location_id) && $location_id !== '') {
                        $editedParams[]       = "location_id";
                        $draft->location_id   = $location_id;
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
