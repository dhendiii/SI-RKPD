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
        $summary            = (isset($input['summary']))      ? $input['summary']   : null;
        $tipeDraft          = (isset($input['tipeDraft']))    ? $input['tipeDraft'] : null;
        $status             = (isset($input['status']))       ? $input['status']    : null;
        $prioritas          = (isset($input['prioritas']))    ? $input['prioritas'] : 0;

        if (!isset($summary) || $summary == '') {
            $missingParams[] = "summary";
        }
        if (!isset($tipeDraft) || $tipeDraft == '') {
            $missingParams[] = "tipeDraft";
        }
        if (!isset($status) || $status == '') {
            $missingParams[] = "status";
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
                        'summary'       => $summary,
                        'tipeDraft'     => $tipeDraft,
                        'status'        => $status,
                        'prioritas'     => $prioritas,
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
                          ->with(array('information'))
                          ->with(array('feedback'))
                          ->with(array('user'))
                          ->with(array('location'))
                          ->with(array('tag'))
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
        $summary            = (isset($input['summary']))        ? $input['summary']         : null;
        $status             = (isset($input['status']))         ? $input['status']          : null;
        $id_information     = (isset($input['id_information'])) ? $input['id_information']  : null;
        $id_feedback        = (isset($input['id_feedback']))    ? $input['id_feedback']     : null;
        $id_user            = (isset($input['id_user']))        ? $input['id_user']         : null;
        $id_location        = (isset($input['id_location']))    ? $input['id_location']     : null;
        $id_tag             = (isset($input['id_tag']))         ? $input['id_tag']          : null;
        $priority           = (isset($input['prioritas']))      ? $input['prioritas']       : null;


        if (!$isError) {
            try {
                $draft      = Draft::find($id);

                if ($draft) {
                    if (isset($summary) && $summary !== '') {
                        $editedParams[]       = "summary";
                        $draft->push('archive_draft',array('summary' => $draft->summary, 'time' => \date("Y-m-d H:i:s")));
                        $draft->summary      = $summary;
                    }

                    if (isset($id_information) && $id_information !== '') {
                        $editedParams[]       = "id_information";
                        $draft->push('id_information', array('id_information' => $id_information));
                    }
                    if (isset($id_feedback) && $id_feedback !== '') {
                        $editedParams[]       = "id_feedback";
                        $draft->push('id_feedback', array('id_feedback' => $id_feedback));
                    }
                    if (isset($id_tag) && $id_tag !== '') {
                        $editedParams[]       = "id_tag";
                        $draft->push('id_tag', array('id_tag' => $id_tag));
                    }

                    if (isset($id_location) && $id_location !== '') {
                        $editedParams[]         = "id_location";
                        $draft->id_location  = $id_location;
                    }
                    if (isset($id_user) && $id_user !== '') {
                        $editedParams[]         = "id_user";
                        $draft->id_user  = $id_user;
                    }

                    if (isset($prioritas) && $prioritas !== '') {
                        $editedParams[]         = "prioritas";
                        $draft->prioritas    = $prioritas;
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
