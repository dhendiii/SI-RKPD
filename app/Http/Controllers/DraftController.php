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
        $message            = (isset($input['message']))      ? $input['message']    : null;
        $draft_tipe         = (isset($input['draft_tipe']))   ? $input['draft_tipe'] : null;
        $status             = (isset($input['status']))       ? $input['status']     : null;
        $prioritas          = (isset($input['prioritas']))    ? $input['prioritas']  : 0;
        $user_id            = (isset($input['user_id']))      ? $input['user_id']     : null;
        $tag_id             = (isset($input['tag_id']))       ? $input['tag_id']     : null;


        if (!isset($message) || $message == '') {
            $missingParams[] = "message";
        }
        if (!isset($draft_tipe) || $draft_tipe == '') {
            $missingParams[] = "draft_tipe";
        }
        if (!isset($status) || $status == '') {
            $missingParams[] = "status";
        }
        if (!isset($user_id) || $user_id == '') {
            $missingParams[] = "user_id";
        }
        if (!isset($tag_id) || $tag_id == '') {
            $missingParams[] = "tag_id";
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
                        'message'       => $message,
                        'draft_tipe'    => $draft_tipe,
                        'status'        => $status,
                        'prioritas'     => $prioritas,
                        'tag_id'        => json_decode($tag_id, true),
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
        $message            = (isset($input['message']))        ? $input['message']         : null;
        $status             = (isset($input['status']))         ? $input['status']          : null;
        $information_id     = (isset($input['information_id'])) ? $input['information_id']  : null;
        $feedback_id        = (isset($input['feedback_id']))    ? $input['feedback_id']     : null;
        $location_id        = (isset($input['location_id']))    ? $input['location_id']     : null;
        $tag_id             = (isset($input['tag_id']))         ? $input['tag_id']          : null;
        $priority           = (isset($input['prioritas']))      ? $input['prioritas']       : null;


        if (!$isError) {
            try {
                $draft      = Draft::find($id);

                if ($draft) {
                    if (isset($message) && $message !== '') {
                        $editedParams[]       = "message";
                        $draft->push('archive_draft',array('message' => $draft->message, 'time' => \date("Y-m-d H:i:s")));
                        $draft->message      = $message;
                    }

                    if (isset($information_id) && $information_id !== '') {
                        $editedParams[]       = "information_id";
                        $draft->push('information_id', array('information_id' => $information_id));
                    }
                    if (isset($feedback_id) && $feedback_id !== '') {
                        $editedParams[]       = "feedback_id";
                        $draft->push('feedback_id', array('feedback_id' => $feedback_id));
                    }
                    if (isset($tag_id) && $tag_id !== '') {
                        $editedParams[]       = "tag_id";
                        $draft->push('tag_id', array('tag_id' => $tag_id));
                    }

                    if (isset($location_id) && $location_id !== '') {
                        $editedParams[]       = "location_id";
                        $draft->location_id   = $location_id;
                    }

                    if (isset($prioritas) && $prioritas !== '') {
                        $editedParams[]       = "prioritas";
                        $draft->prioritas     = $prioritas;
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
