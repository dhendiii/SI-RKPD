<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Location;

class UserController extends Controller {
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
      $message            = "Mengambil semua data user sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $limit              = (isset($input['limit']))     ? $input['limit']    : null;
      $offset             = (isset($input['offset']))    ? $input['offset']   : null;

      if (!$isError) {
          try {
              $result = User::take($limit)->skip($offset)->get();

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
      $message            = "Menyimpan data user baru sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $nama_depan         = (isset($input['nama_depan']))    ? $input['nama_depan']     : null;
      $nama_belakang      = (isset($input['nama_belakang'])) ? $input['nama_belakang']  : null;
      $user_tipe          = (isset($input['user_tipe']))     ? $input['user_tipe']      : null;
      $no_ktp             = (isset($input['no_ktp']))        ? $input['no_ktp']         : null;
      $email              = (isset($input['email']))         ? $input['email']          : null;
      $password           = (isset($input['password']))      ? $input['password']       : null;
      $user_poin          = (isset($input['user_poin']))     ? $input['user_poin']      : 0;
      $lencana            = (isset($input['lencana']))       ? array($input['lencana']) : array();

      if (!isset($no_ktp) || $no_ktp == '') {
          $missingParams[] = "no_ktp";
      }
      if (!isset($nama_depan) || $nama_depan == '') {
          $missingParams[] = "nama_depan";
      }
      if (!isset($user_tipe) || $user_tipe == '') {
          $missingParams[] = "user_tipe";
      }
      if (!isset($email) || $email == '') {
          $missingParams[] = "email";
      }
      if (!isset($password) || $password == '') {
          $missingParams[] = "password";
      }

      if (isset($missingParams)) {
          $isError    = TRUE;
          $response   = "FAILED";
          $statusCode = 400;
          $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
      }

      if (!$isError) {
          try {
              $checker = User::where('no_ktp', $no_ktp)->first();

              if (!$checker) {
                  $user   = User::create(array(
                      'nama_depan'      => $nama_depan,
                      'nama_belakang'   => $nama_belakang,
                      'user_tipe'       => $user_tipe,
                      'no_ktp'          => $no_ktp,
                      'email'           => $email,
                      'password'        => $password,
                      'user_poin'       => $user_poin,
                      'lencana'         => $lencana,
                  ));

                  $result['id']   = $user->_id;
              } else {
                  throw new \Exception("User dengan nomor KTP $no_ktp sudah terdaftar.");
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
      $message            = "Mengambil data user dengan id $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $result = User::where('_id', $id)->first();
                    $result->draft = $result->getDraft();
                    unset($result->draft_id);

                    $result->information = $result->getInformation();
                    unset($result->information_id);

                    $result->feedback = $result->getFeedback();
                    unset($result->feedback_id);

              if (!$result) {
                  throw new \Exception("User dengan id $id tidak ditemukan.");
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
      $message            = "Menyunting data user sukses.";
      $isError            = FALSE;
      $editedParams       = null;

      $input              = $request->all();
      $nama_depan         = (isset($input['nama_depan']))       ? $input['nama_depan']      : null;
      $nama_belakang      = (isset($input['nama_belakang']))    ? $input['nama_belakang']   : null;
      $user_tipe          = (isset($input['user_tipe']))        ? $input['user_tipe']       : null;
      $no_ktp             = (isset($input['no_ktp']))           ? $input['no_ktp']          : null;
      $email              = (isset($input['email']))            ? $input['email']           : null;
      $password           = (isset($input['password']))         ? $input['password']        : null;
      $user_poin          = (isset($input['user_poin']))        ? $input['user_poin']       : null;
      $lencana            = (isset($input['lencana']))          ? $input['lencana']         : null;
      $location_id        = (isset($input['location_id']))      ? $input['location_id']     : null;

      if (!$isError) {
          try {
              $user      = User::find($id);

              if ($user) {
                  if (isset($nama_depan) && $nama_depan !== '') {
                      $editedParams[]       = "nama_depan";
                      $user->nama_depan      = $nama_depan;
                  }
                  if (isset($nama_belakang) && $nama_belakang !== '') {
                      $editedParams[]       = "nama_belakang";
                      $user->nama_belakang   = $nama_belakang;
                  }
                  if (isset($user_tipe) && $user_tipe !== '') {
                      $editedParams[]       = "user_tipe";
                      $user->user_tipe       = $user_tipe;
                  }
                  if (isset($no_ktp) && $no_ktp !== '') {
                      $editedParams[]       = "no_ktp";
                      $user->no_ktp          = $no_ktp;
                  }
                  if (isset($email) && $email !== '') {
                      $editedParams[]       = "email";
                      $user->email          = $email;
                  }
                  if (isset($password) && $password !== '') {
                      $editedParams[]       = "password";
                      $user->password       = $password;
                  }
                  if (isset($user_poin) && $user_poin !== '') {
                      $editedParams[]       = "user_poin";
                      $user->user_poin           = $user_poin;
                  }
                  if (isset($lencana) && $lencana !== '') {
                      $editedParams[]       = "lencana";
                      $user->push('lencana', array('lencana' => $lencana, 'time' => \date("Y-m-d H:i:s")));
                  }
                  if (isset($location_id) && $location_id !== '') {
                      $editedParams[]       = "location_id";
                      $user->location_id    = $location_id;
                  }

                  if (isset($editedParams)) {
                      $user->save();
                      $message    = $message." Data yang berubah : {".implode(', ', $editedParams)."}";
                  } else {
                      $message    = $message." Tidak ada data yang berubah.";
                  }
              } else {
                  throw new \Exception("User dengan id $id tidak ditemukan.");
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
      $message            = "Menghapus data user dengan $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $user      = User::find($id);

              if ($user) {
                  $user->delete();
              } else {
                  throw new \Exception("User dengan id $id tidak ditemukan.");
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
