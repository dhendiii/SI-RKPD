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

      $informasi_tipe     = (isset($input['informasi_tipe']))   ? $input['informasi_tipe']  : null;
      $konten             = (isset($input['konten']))           ? $input['konten']          : null;

      $jumlah             = (isset($input['jumlah']))           ? $input['jumlah']          : 0;
      $satuan             = (isset($input['satuan']))           ? $input['satuan']          : null;

      $like               = (isset($input['like']))             ? $input['like']            : 0;
      $dislike            = (isset($input['dislike']))          ? $input['dislike']         : 0;

      $verifikasi         = (isset($input['verifikasi']))       ? $input['verifikasi']      : null;
      $verifikasi_ket     = (isset($input['verifikasi_ket']))   ? $input['verifikasi_ket']  : null;

      $user_id            = (isset($input['user_id']))          ? $input['user_id']         : null;
      $draft_id           = (isset($input['draft_id']))         ? $input['draft_id']        : null;

      if (!isset($konten) || $konten == '') {
          $missingParams[] = "konten";
      }
      if (!isset($informasi_tipe) || $informasi_tipe == '') {
          $missingParams[] = "informasi_tipe";
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
                  $informasi   = Information::create(array(
                      'informasi_tipe'  => $informasi_tipe,
                      'konten'          => $konten,
                      'jumlah'          => $jumlah,
                      'satuan'          => $satuan,
                      'like'            => $like,
                      'dislike'         => $dislike,
                      'verifikasi'      => $verifikasi,
                      'verifikasi_ket'  => $verifikasi_ket,
                      'user_id'         => $user_id,
                      'draft_id'        => $draft_id,
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
              $result = Information::where('_id', $id)
                            //->with('user')
                            ->first();

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

      $konten             = (isset($input['konten']))           ? $input['konten']          : null;

      $jumlah             = (isset($input['jumlah']))           ? $input['jumlah']          : 0;
      $satuan             = (isset($input['satuan']))           ? $input['satuan']          : null;

      $like               = (isset($input['like']))             ? $input['like']            : 0;
      $dislike            = (isset($input['dislike']))          ? $input['dislike']         : 0;

      $verifikasi         = (isset($input['verifikasi']))       ? $input['verifikasi']      : null;
      $verifikasi_ket     = (isset($input['verifikasi_ket']))   ? $input['verifikasi_ket']  : null;


      if (!$isError) {
          try {
              $informasi    = Information::find($id);

              if ($informasi) {
                  if (isset($konten) && $konten !== '') {
                      $editedParams[]       = "konten";
                      $informasi->push('archive_informasi',array('konten' => $informasi->konten, 'time' => \date("Y-m-d H:i:s")));
                      $informasi->konten      = $konten;
                  }
                  if (isset($jumlah) && $jumlah !== '') {
                      $editedParams[]       = "jumlah";
                      $informasi->jumlah   = $jumlah;
                  }
                  if (isset($satuan) && $satuan !== '') {
                      $editedParams[]       = "satuan";
                      $informasi->satuan   = $satuan;
                  }

                  if (isset($like) && $like !== '') {
                      $editedParams[]       = "like";
                      $informasi->like   = $like;
                  }
                  if (isset($dislike) && $dislike !== '') {
                      $editedParams[]       = "dislike";
                      $informasi->dislike   = $dislike;
                  }

                  if (isset($verifikasi) && $verifikasi !== '') {
                      $editedParams[]       = "verifikasi";
                      $informasi->verifikasi   = $verifikasi;
                  }
                  if (isset($verifikasi_ket) && $verifikasi_ket !== '') {
                      $editedParams[]       = "verifikasi_ket";
                      $informasi->verifikasi_ket   = $verifikasi_ket;
                  }

                  if (isset($editedParams)) {
                      $informasi->save();
                      $konten    = $konten." Data yang berubah : {".implode(', ', $editedParams)."}";
                  } else {
                      $konten    = $konten." Tidak ada data yang berubah.";
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
