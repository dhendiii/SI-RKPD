<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Tag;
use App\Models\SKPD;

class TagController extends Controller {
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
      $message            = "Mengambil semua data tag sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $limit              = (isset($input['limit']))     ? $input['limit']    : null;
      $offset             = (isset($input['offset']))    ? $input['offset']   : null;

      if (!$isError) {
          try {
              $result = Tag::take($limit)->skip($offset)->get();

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
      $message            = "Menyimpan data tag baru sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      $input              = $request->all();
      $nama               = (isset($input['nama']))    ? $input['nama']           : null;
      $skpd_id            = (isset($input['skpd_id'])) ? $input['skpd_id'] : null;

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
              $checker      = Tag::where('nama', $nama)->first();

              if (!$checker) {
                  $tag   = Tag::create(array(
                      'nama'     => $nama,
                      'skpd_id'  => json_decode($skpd_id, true),
                     ));

                  $result['id']   = $tag->_id;
              } else {
                  throw new \Exception("Tag dengan nama $nama sudah terdaftar.");
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
      $message            = "Mengambil data tag dengan id $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $result = Tag::where('_id', $id)->first();
              $result->skpd = $result->getSkpd();
              unset($result->skpd_id);

              if (!$result) {
                  throw new \Exception("Tag dengan id $id tidak ditemukan.");
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
      $message            = "Menyunting data tag sukses.";
      $isError            = FALSE;
      $editedParams       = null;

      $input              = $request->all();
      $nama               = (isset($input['nama']))    ? $input['nama']    : null;
      $skpd_id            = (isset($input['skpd_id'])) ? $input['skpd_id'] : null;

      if (!$isError) {
          try {
              $tag      = Tag::find($id);

              if ($tag) {
                  if (isset($nama) && $nama !== '') {
                      $editedParams[]       = "nama";
                      $tag->nama            = $nama;
                  }

                  if (isset($skpd_id) && $skpd_id !== '') {
                      $editedParams[]       = "skpd_id";
                      $tag->push('skpd_id');
                      //$tag->push('skpd_id', array('skpd_id' => $skpd_id));
                  }

                  if (isset($editedParams)) {
                      $tag->save();
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
      $message            = "Menghapus data tag dengan $id sukses.";
      $isError            = FALSE;
      $missingParams      = null;

      if (!$isError) {
          try {
              $tag      = Tag::find($id);

              if ($tag) {
                  $tag->delete();
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
