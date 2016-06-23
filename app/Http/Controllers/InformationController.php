<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Information;
use App\Models\User;

class InformationController extends Controller {
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
      $message            = "Mengambil semua data informasi sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $limit              = (isset($input['limit']))     ? $input['limit']    : null;
      $offset             = (isset($input['offset']))    ? $input['offset']   : null;

      if (!$isError) {
          try {
              $result = Information::take($limit)->skip($offset)->get();

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
      $message            = "Menyimpan data informasi baru sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $message            = (isset($input['message']))          ? $input['message']      : null;
      $tipeInformasi      = (isset($input['tipeInformasi']))    ? $input['tipeInformasi']: null;
      $status             = (isset($input['status']))           ? $input['status']       : null;

      if (!isset($message) || $message == '') {
          $missingParams[] = "message";
      }
      if (!isset($tipeInformasi) || $tipeInformasi == '') {
          $missingParams[] = "tipeInformasi";
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
                  $informasi   = Information::create(array(
                      'message'         => $message,
                      'tipeInformasi'   => $tipeInformasi,
                      'status'          => $status,
                     ));

                  $result['id']   = $informasi->_id;
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
      $message            = "Mengambil data informasi dengan id $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $result = Information::where('_id', $id)->with('user')->first();

              if (!$result) {
                  throw new \Exception("Informasi dengan id $id tidak ditemukan.");
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
      $message            = "Menyunting data informasi sukses.";
      $isError            = FALSE;
      $editedParams       = null;

      $input              = $request->all();
      $message            = (isset($input['message']))    ? $input['message']   : null;
      $status             = (isset($input['status'])) ? $input['status']: null;
      $id_user             = (isset($input['id_user'])) ? $input['id_user']: null;

      if (!$isError) {
          try {
              $informasi    = Information::find($id);

              if ($informasi) {
                  if (isset($message) && $message !== '') {
                      $editedParams[]       = "message";
                      $informasi->push('archive_informasi',array('message' => $informasi->message, 'time' => \date("Y-m-d H:i:s")));
                      $informasi->message      = $message;
                  }
                  if (isset($status) && $status !== '') {
                      $editedParams[]       = "status";
                      $informasi->status   = $status;
                  }
                  if (isset($id_user) && $id_user !== '') {
                      $editedParams[]       = "id_user";
                      $informasi->id_user   = $id_user;
                  }

                  if (isset($editedParams)) {
                      $informasi->save();
                      $message    = $message." Data yang berubah : {".implode(', ', $editedParams)."}";
                  } else {
                      $message    = $message." Tidak ada data yang berubah.";
                  }
              } else {
                  throw new \Exception("Informasi dengan id $id tidak ditemukan.");
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
      $message            = "Menghapus data informasi dengan $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $informasi      = Information::find($id);

              if ($informasi) {
                  $informasi->delete();
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
