<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\SKPD;

class SKPDController extends Controller {
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
      $message            = "Mengambil semua data SKPD sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $limit              = (isset($input['limit']))     ? $input['limit']    : null;
      $offset             = (isset($input['offset']))    ? $input['offset']   : null;

      if (!$isError) {
          try {
              $result = SKPD::take($limit)->skip($offset)->get();

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
      $message            = "Menyimpan data SKPD baru sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $nama       = (isset($input['nama']))     ? $input['nama']    : null;
      $tags               = (isset($input['tags']))             ? $input['tags']            : null;

      if (!isset($nama) || $nama == '') {
          $missingParams[] = "nama";
      }

      if (isset($missingParams)) {
          $isError    = TRUE;
          $response   = "FAILED";
          $statusCode = 400;
          $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
      }

      if (!$isError) {
          try {
              $checker      = SKPD::where('nama', $nama)->first();

              if (!$checker) {
                  $skpd   = SKPD::create(array(
                      'nama'    => $nama,
                      'tags'            => json_decode($tags, true),
                     ));

                  $result['id']   = $skpd->_id;
              } else {
                  throw new \Exception("SKPD dengan nama $nama sudah terdaftar.");
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
      $message            = "Mengambil data SKPD dengan id $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $result = SKPD::where('_id', $id)
                        ->first();

              if (!$result) {
                  throw new \Exception("SKPD dengan id $id tidak ditemukan.");
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
      $message            = "Menyunting data SKPD sukses.";
      $isError            = FALSE;
      $editedParams       = null;

      $input              = $request->all();
      $nama       = (isset($input['nama']))     ? $input['nama']    : null;
      $tags               = (isset($input['tags']))             ? $input['tags']            : null;

      if (!$isError) {
          try {
              $skpd      = SKPD::find($id);

              if ($skpd) {
                  if (isset($nama) && $nama !== '') {
                      $editedParams[]       = "nama";
                      $skpd->nama      = $nama;
                  }
                  if (isset($tags) && $tags !== '') {
                      $editedParams[]       = "tags";
                      $draft->tags          = json_decode($tags, true);
                  }

                  if (isset($editedParams)) {
                      $skpd->save();
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
      $message            = "Menghapus data SKPD dengan $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $skpd      = SKPD::find($id);

              if ($skpd) {
                  $skpd->delete();
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
