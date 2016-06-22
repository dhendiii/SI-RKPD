<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;

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
      $namaDepan          = (isset($input['namaDepan']))    ? $input['namaDepan']   : null;
      $namaBelakang       = (isset($input['namaBelakang'])) ? $input['namaBelakang']: null;
      $tipeUser           = (isset($input['tipeUser']))     ? $input['tipeUser']    : null;
      $noKTP              = (isset($input['noKTP']))        ? $input['noKTP']       : null;
      $email              = (isset($input['email']))        ? $input['email']       : null;
      $password           = (isset($input['password']))     ? $input['password']    : null;
      $poin               = (isset($input['poin']))         ? $input['poin']        : 0;
      $lencana            = (isset($input['lencana']))      ? $input['lencana']     : null;

      if (!isset($noKTP) || $noKTP == '') {
          $missingParams[] = "noKTP";
      }
      if (!isset($namaDepan) || $namaDepan == '') {
          $missingParams[] = "namaDepan";
      }
      if (!isset($tipeUser) || $tipeUser == '') {
          $missingParams[] = "tipeUser";
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
              $checker      = User::where('noKTP', $noKTP)->first();

              if (!$checker) {
                  $user   = User::create(array(
                      'namaDepan'       => $namaDepan,
                      'namaBelakang'    => $namaBelakang,
                      'tipeUser'        => $tipeUser,
                      'noKTP'           => $noKTP,
                      'email'           => $email,
                      'password'        => $password,
                      'poin'            => $poin,
                      'lencana'         => $lencana,
                  ));

                  $result['id']   = $user->_id;
              } else {
                  throw new \Exception("User dengan nomor KTP $noKTP sudah terdaftar.");
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
      $namaDepan          = (isset($input['namaDepan']))    ? $input['namaDepan']   : null;
      $namaBelakang       = (isset($input['namaBelakang'])) ? $input['namaBelakang']: null;
      $tipeUser           = (isset($input['tipeUser']))     ? $input['tipeUser']    : null;
      $noKTP              = (isset($input['noKTP']))        ? $input['noKTP']       : null;
      $email              = (isset($input['email']))        ? $input['email']       : null;
      $password           = (isset($input['password']))     ? $input['password']    : null;
      $poin               = (isset($input['poin']))         ? $input['poin']        : 0;
      $lencana            = (isset($input['lencana']))      ? $input['lencana']     : null;

      if (!$isError) {
          try {
              $user      = User::find($id);

              if ($user) {
                  if (isset($namaDepan) && $namaDepan !== '') {
                      $editedParams[]       = "namaDepan";
                      $user->namaDepan      = $namaDepan;
                  }
                  if (isset($namaBelakang) && $namaBelakang !== '') {
                      $editedParams[]       = "namaBelakang";
                      $user->namaBelakang   = $namaBelakang;
                  }
                  if (isset($tipeUser) && $tipeUser !== '') {
                      $editedParams[]       = "tipeUser";
                      $user->tipeUser       = $tipeUser;
                  }
                  if (isset($noKTP) && $noKTP !== '') {
                      $editedParams[]       = "noKTP";
                      $user->noKTP          = $noKTP;
                  }
                  if (isset($email) && $email !== '') {
                      $editedParams[]       = "email";
                      $user->email          = $email;
                  }
                  if (isset($password) && $password !== '') {
                      $editedParams[]       = "password";
                      $user->password       = $password;
                  }
                  if (isset($poin) && $poin !== '') {
                      $editedParams[]       = "poin";
                      $user->poin           = $poin;
                  }
                  if (isset($lencana) && $lencana !== '') {
                      $editedParams[]       = "lencana";
                      $user->push('lencana_history', array('lencana' => $user->lencana, 'time' => \date("Y-m-d H:i:s")));

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
