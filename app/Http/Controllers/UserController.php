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
      $nama_depan         = (isset($input['nama_depan']))           ? $input['nama_depan']          : null;
      $nama_belakang      = (isset($input['nama_belakang']))        ? $input['nama_belakang']       : null;
      $email              = (isset($input['email']))                ? $input['email']               : null;
      $no_hp              = (isset($input['no_hp']))                ? $input['no_hp']               : null;
      $password           = (isset($input['password']))             ? $input['password']            : null;

      // lokasi
      $lokasi_detail      = (isset($input['lokasi_detail']))        ? $input['lokasi_detail']       : null;
      $lokasi_kelurahan   = (isset($input['lokasi_kelurahan']))     ? $input['lokasi_kelurahan']    : null;
      $lokasi_kecamatan   = (isset($input['lokasi_kecamatan']))     ? $input['lokasi_kecamatan']    : null;

      $user_tipe          = (isset($input['user_tipe']))            ? $input['user_tipe']           : null;
      $no_ktp             = (isset($input['no_ktp']))               ? $input['no_ktp']              : null;
      $skpd               = (isset($input['skpd']))                 ? $input['skpd']                : null;
      $skpd_role          = (isset($input['skpd_role']))            ? $input['skpd_role']           : null;

      $user_poin          = (isset($input['user_poin']))            ? $input['user_poin']           : 0;
      $lencana            = (isset($input['lencana']))              ? $input['lencana']             : null;

      if (!isset($nama_depan) || $nama_depan == '') {
          $missingParams[] = "nama_depan";
      }
      if (!isset($email) || $email == '') {
          $missingParams[] = "email";
      }
      if (!isset($no_hp) || $no_hp == '') {
          $missingParams[] = "no_hp";
      }

      if (!isset($lokasi_kelurahan) || $lokasi_kelurahan == '') {
          $missingParams[] = "lokasi_kelurahan";
      }

      if (!isset($lokasi_kecamatan) || $lokasi_kecamatan == '') {
          $missingParams[] = "lokasi_kecamatan";
      }

      if (!isset($password) || $password == '') {
          $missingParams[] = "password";
      }

      if (!isset($no_ktp) || $no_ktp == '') {
          $missingParams[] = "no_ktp";
      }

      if (!isset($user_tipe) || $user_tipe == '') {
          $missingParams[] = "user_tipe";
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
                      'nama_depan'          => $nama_depan,
                      'nama_belakang'       => $nama_belakang,
                      'email'               => $email,
                      'no_hp'               => $no_hp,
                      'password'            => $password,
                      'lokasi_detail'       => $lokasi_detail,
                      'lokasi_kelurahan'    => $lokasi_kelurahan,
                      'lokasi_kecamatan'    => $lokasi_kecamatan,
                      'user_tipe'           => $user_tipe,
                      'no_ktp'              => $no_ktp,
                      'skpd'                => $skpd,
                      'skpd_role'           => $skpd_role,
                      'user_poin'           => $user_poin,
                      'lencana'             => json_decode($lencana, true),
                  ));

                  $result['id']   = $user->_id;

                  $result   = array(
                      '_id'                 => $user->_id,
                      'nama_depan'          => $user->nama_depan,
                      'nama_belakang'       => $user->nama_belakang,
                      'user_tipe'           => $user->user_tipe,
                  );


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
            //   $result->draft = $result->getDraft();
            //   unset($result->draft_id);
            //
            //   $result->information = $result->getInformation();
            //   unset($result->information_id);
              //
            //   $result->feedback = $result->getFeedback();
            //   unset($result->feedback_id);

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
      $nama_depan         = (isset($input['nama_depan']))       ? $input['nama_depan']          : null;
      $nama_belakang      = (isset($input['nama_belakang']))    ? $input['nama_belakang']       : null;
      $email              = (isset($input['email']))            ? $input['email']               : null;
      $password           = (isset($input['password']))         ? $input['password']            : null;
      $lokasi_detail      = (isset($input['lokasi_detail']))    ? $input['lokasi_detail']       : null;
      $lokasi_kelurahan   = (isset($input['lokasi_kelurahan'])) ? $input['lokasi_kelurahan']    : null;
      $lokasi_kecamatan   = (isset($input['lokasi_kecamatan'])) ? $input['lokasi_kecamatan']    : null;
      $skpd               = (isset($input['skpd']))             ? $input['skpd']                : null;
      $skpd_role          = (isset($input['skpd_role']))        ? $input['skpd_role']           : null;
      $user_poin          = (isset($input['user_poin']))        ? $input['user_poin']           : null;
      $lencana            = (isset($input['lencana']))          ? $input['lencana']             : null;


      if (!$isError) {
          try {
              $user      = User::find($id);

              if ($user) {
                  if (isset($nama_depan) && $nama_depan !== '') {
                      $editedParams[]           = "nama_depan";
                      $user->nama_depan         = $nama_depan;
                  }
                  if (isset($nama_belakang) && $nama_belakang !== '') {
                      $editedParams[]           = "nama_belakang";
                      $user->nama_belakang      = $nama_belakang;
                  }
                  if (isset($email) && $email !== '') {
                      $editedParams[]           = "email";
                      $user->email              = $email;
                  }
                  if (isset($password) && $password !== '') {
                      $editedParams[]           = "password";
                      $user->password           = $password;
                  }
                  if (isset($lokasi_detail) && $lokasi_detail !== '') {
                      $editedParams[]           = "lokasi_detail";
                      $user->lokasi_detail      = $lokasi_detail;
                  }
                  if (isset($lokasi_kelurahan) && $lokasi_kelurahan !== '') {
                      $editedParams[]           = "lokasi_kelurahan";
                      $user->lokasi_kelurahan   = $lokasi_kelurahan;
                  }
                  if (isset($lokasi_kecamatan) && $lokasi_kecamatan !== '') {
                      $editedParams[]           = "lokasi_kecamatan";
                      $user->lokasi_kecamatan   = $lokasi_kecamatan;
                  }
                  if (isset($skpd) && $skpd !== '') {
                      $editedParams[]           = "skpd";
                      $user->skpd               = $skpd;
                  }
                  if (isset($skpd_role) && $skpd_role !== '') {
                      $editedParams[]           = "skpd_role";
                      $user->skpd_role          = $skpd_role;
                  }
                  if (isset($user_poin) && $user_poin !== '') {
                      $editedParams[]           = "user_poin";
                      $user->user_poin          = $user_poin;
                  }
                  if (isset($lencana) && $lencana !== '') {
                      $editedParams[]           = "lencana";
                      $user->push('lencana', array('lencana' => $lencana, 'time' => \date("Y-m-d H:i:s")));
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

  public function login(Request $request){
      $returnData       = array();
      $response         = "OK";
      $statusCode       = 200;
      $result           = null;
      $message          = "Login sukses.";
      $isError          = FALSE;
      $missingParams    = null;

      $input            = $request->all();
      $email              = (isset($input['email']))            ? $input['email']               : null;
      $password           = (isset($input['password']))         ? $input['password']            : null;

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
              $emailOK  = User::where('email', $email)->first();
              if (!$emailOK) {
                  throw new \Exception("Email $email tidak ditemukan");
              } else {
                  $user     = ($emailOK->password == $password) ? true : false;

                  if ($user) {
                      $result   = array(
                          '_id'             => $emailOK->_id,
                          'nama_depan'      => $emailOK->nama_depan,
                          'nama_belakang'   => $emailOK->nama_belakang,
                          'user_tipe'       => $emailOK->user_tipe,
                      );
                  } else {
                      throw new \Exception("Username dan password tidak cocok.");
                  }
              }


              //   $user     = User::where('email', 'dhendi@y.co')->first();

              //   var_dump(User::where('email', 'dhendi@y.co')->first());
              //   var_dump(User::find('email', $email));

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

  //login
  // ini buat nyocokin email psswd
  // return faild sama ok

}
