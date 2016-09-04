<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Draft;
use App\Models\Feedback;

class FeedbackController extends Controller {
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
        $message            = "Mengambil semua data feedback sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $result = Feedback::take($limit)->skip($offset)->get();

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
        $message            = "Menyimpan data feedback baru sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();

        $konten             = (isset($input['konten']))             ? $input['konten']              : null;
        $foto               = ($request->hasFile('foto'))           ? $request->file('foto')        : null;
        $proposal           = ($request->hasFile('proposal'))       ? $request->file('proposal')    : null;

        $tipe               = (isset($input['tipe']))               ? $input['tipe']                : null;

        $status             = (isset($input['status']))             ? $input['status']              : null;
        $status_ket         = (isset($input['status_ket']))         ? $input['status_ket']          : null;

        $like               = (isset($input['like']))               ? $input['like']                : 0;
        $dislike            = (isset($input['dislike']))            ? $input['dislike']             : 0;
        $like_users         = (isset($input['like_users']))         ? $input['like_users']          : null;
        $dislike_users      = (isset($input['dislike_users']))      ? $input['dislike_users']       : null;

        $user_id            = (isset($input['user_id']))            ? $input['user_id']             : null;
        $draft_id           = (isset($input['draft_id']))           ? $input['draft_id']            : null;

        if (!isset($konten) || $konten == '') {
            $missingParams[] = "konten";
        }
        if (!isset($tipe) || $tipe == '') {
            $missingParams[] = "tipe";
        }

        if (!isset($user_id) || $user_id == '') {
            $missingParams[] = "user_id";
        }
        if (!isset($draft_id) || $draft_id == '') {
            $missingParams[] = "draft_id";
        }

        if (isset($missingParams)) {
            $isError    = TRUE;
            $response   = "FAILED";
            $statusCode = 400;
            $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
        }

        if (!$isError) {
            try {
                $feedback   = Feedback::create(array(
                    'konten'            => $konten,
                    'tipe'              => $tipe,
                    'status'            => $status,
                    'status_ket'        => $status_ket,
                    'like'              => $like,
                    'dislike'           => $dislike,
                    'like_users'        => array($like_users),
                    'dislike_users'     => array($dislike_users),
                    'user_id'           => $user_id,
                    'draft_id'          => $draft_id,
                ));

                if ($foto) {
                    $extension = $foto->getClientOriginalExtension();
                    if (in_array($extension, array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG'))){
                        $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                        $uploadedDest   = 'images/'.$feedback->_id;
                        $uploadedName   = $random_name.'.'.$extension;

                        $foto->move($uploadedDest, $uploadedName);
                        $feedback->foto   = $uploadedDest.'/'.$uploadedName;
                        $feedback->save();
                    }
                }

                if ($proposal) {
                    $extension  = $proposal->getClientOriginalExtension();
                    if (in_array($extension,  array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                        $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);
                        $uploadedDest   = 'docs/'.$feedback->_id;
                        $uploadedName   = $random_name.'.'.$extension;

                        $proposal->move($uploadedDest, $uploadedName);
                        $feedback->proposal   = $uploadedDest.'/'.$uploadedName;
                        $feedback->save();
                    }
                }

                $result = $feedback;

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
        $message            = "Mengambil data feedback dengan id $id sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $result = Feedback::where('_id', $id)
                            ->with(array('user'))
                            ->first();

                if (!$result) {
                    throw new \Exception("Feedback dengan id $id tidak ditemukan.");
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
        $message            = "Menyunting data feedback sukses.";
        $isError            = FALSE;
        $editedParams       = null;

        $input                      = $request->all();
        $status                     = (isset($input['status']))                 ? $input['status']              : null;
        $status_ket                 = (isset($input['status_ket']))             ? $input['status_ket']          : null;
        $like                       = (isset($input['like']))                   ? $input['like']                : null;
        $dislike                    = (isset($input['dislike']))                ? $input['dislike']             : null;
        $like_users                 = (isset($input['like_users']))             ? $input['like_users']          : null;
        $dislike_users              = (isset($input['dislike_users']))          ? $input['dislike_users']       : null;
        $foto                       = ($request->hasFile('foto'))               ? $request->file('foto')        : null;
        $proposal                   = ($request->hasFile('proposal'))           ? $request->file('proposal')    : null;

        if (!$isError) {
            try {
                $feedback      = Feedback::find($id);

                if ($feedback) {
                    if (isset($status) && $status !== '') {
                        $editedParams[]         = "status";
                        $feedback->status         = $status;
                    }
                    if (isset($status_ket) && $status_ket !== '') {
                        $editedParams[]         = "status_ket";
                        $feedback->status_ket         = $status_ket;
                    }
                    if (isset($like) && $like !== '') {
                        $editedParams[]         = "like";
                        $feedback->like         = $like;
                    }
                    if (isset($dislike) && $dislike !== '') {
                        $editedParams[]         = "dislike";
                        $feedback->dislike         = $dislike;
                    }

                    if (isset($like_users) && $like_users !== '') {
                        if (!in_array($like_users, $feedback->like_users)) {
                            $editedParams[]       = "like";

                            if (in_array($like_users, $feedback->dislike_users)) {
                                $feedback->decrement("dislike");
                                $feedback->pull('dislike_users', $like_users);
                            }

                            $feedback->increment("like");
                            $feedback->push('like_users', $like_users);

                            $result = array(
                                'like'      => $feedback->like,
                                'dislike'   => $feedback->dislike,
                            );
                        }
                    }

                    if (isset($dislike_users) && $dislike_users !== '') {
                        if (!in_array($dislike_users, $feedback->dislike_users)) {
                            $editedParams[]       = "dislike";

                            if (in_array($dislike_users, $feedback->like_users)) {
                                $feedback->decrement("like");
                                $feedback->pull('like_users', $dislike_users);
                            }

                            $feedback->increment("dislike");
                            $feedback->push('dislike_users', $dislike_users);

                            $result = array(
                                'like'      => $feedback->like,
                                'dislike'   => $feedback->dislike,
                            );
                        }
                    }

                    if ($foto) {
                        $extension  = $foto->getClientOriginalExtension();
                        if (in_array($extension, array('jpg', 'jpeg','png', 'JPG', 'JPEG', 'PNG',))) {
                            $editedParams[] = "foto";
                            if (file_exists($feedback->foto)) { unlink($feedback->foto); }

                            $uploadedDest   = 'images/'.$id;
                            $uploadedName   = 'foto.'.$extension;

                            $foto->move($uploadedDest, $uploadedName);
                            $feedback->foto   = $uploadedDest.'/'.$uploadedName;
                        }
                    }
                    if ($proposal) {
                        $extension  = $proposal->getClientOriginalExtension();
                        if (in_array($extension, array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                            $editedParams[] = "proposal";
                            if (file_exists($feedback->proposal)) { unlink($feedback->proposal); }

                            $uploadedDest   = 'images/'.$id;
                            $uploadedName   = 'proposal.'.$extension;

                            $proposal->move($uploadedDest, $uploadedName);
                            $feedback->proposal   = $uploadedDest.'/'.$uploadedName;
                        }
                    }

                    if (isset($editedParams)) {
                        $feedback->save();

                        $message    = $message." Data yang berubah : {".implode(', ', $editedParams)."}";
                    } else {
                        $message    = $message." Tidak ada data yang berubah.";
                    }
                } else {
                    throw new \Exception("Kelas dengan id $id tidak ditemukan.");
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
        $message            = "Menghapus data feedback dengan $id sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        if (!$isError) {
            try {
                $feedback      = Feedback::find($id);

                if ($feedback) {
                    $feedback->delete();
                } else {
                    throw new \Exception("Feed dengan id $id tidak ditemukan.");
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
