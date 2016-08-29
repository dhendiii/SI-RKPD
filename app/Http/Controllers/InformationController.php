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

      $ikp_konten               = (isset($input['ikp_konten']))             ? $input['ikp_konten']             : null;
      $ikp_jumlah               = (isset($input['ikp_jumlah']))             ? $input['ikp_jumlah']             : null;
      $ikp_unit                 = (isset($input['ikp_unit']))               ? $input['ikp_unit']               : null;
      $ikp_verifikasi           = (isset($input['ikp_verifikasi']))         ? $input['ikp_verifikasi']         : null;
      $ikp_verket               = (isset($input['ikp_verket']))             ? $input['ikp_verket']             : null;

      $anggaran                 = (isset($input['anggaran']))               ? $input['anggaran']                : null;
      $anggaran_verifikasi      = (isset($input['anggaran_verifikasi']))    ? $input['anggaran_verifikasi']     : null;
      $anggaran_verket          = (isset($input['anggaran_verket']))        ? $input['anggaran_verket']         : null;
      $sumberanggaran           = (isset($input['sumberanggaran']))         ? $input['sumberanggaran']          : null;

      $jeniskegiatan            = (isset($input['jeniskegiatan']))          ? $input['jeniskegiatan']           : null;
      $keterangan               = (isset($input['keterangan']))             ? $input['keterangan']              : null;

      $foto_verifikasi          = (isset($input['foto_verifikasi']))        ? $input['foto_verifikasi']         : null;
      $foto_verket              = (isset($input['foto_verket']))            ? $input['foto_verket']             : null;

      $proposal_verifikasi      = (isset($input['proposal_verifikasi']))    ? $input['proposal_verifikasi']     : null;
      $proposal_verket          = (isset($input['proposal_verket']))        ? $input['proposal_verket']         : null;


      $foto                     = ($request->hasFile('foto'))               ? $request->file('foto')            : null;
      $proposal                 = ($request->hasFile('proposal'))           ? $request->file('proposal')        : null;

      $user_id                  = (isset($input['user_id']))                ? $input['user_id']                 : null;
      $draft_id                 = (isset($input['draft_id']))               ? $input['draft_id']                : null;

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
                      'ikp_konten'          => $ikp_konten,
                      'ikp_jumlah'          => $ikp_jumlah,
                      'ikp_unit'            => $ikp_unit,
                      'ikp_verifikasi'      => $ikp_verifikasi,
                      'ikp_verket'          => $ikp_verket,
                      'anggaran'            => $anggaran,
                      'anggaran_verifikasi' => $anggaran_verifikasi,
                      'anggaran_verket'     => $anggaran_verket,
                      'sumberanggaran'      => $sumberanggaran,
                      'jeniskegiatan'       => $jeniskegiatan,
                      'keterangan'          => $keterangan,
                      'foto_verifikasi'     => $foto_verifikasi,
                      'foto_verket'         => $foto_verket,
                      'proposal_verifikasi' => $proposal_verifikasi,
                      'proposal_verket'     => $proposal_verket,
                      'foto'                => null,
                      'proposal'            => null,
                      'user_id'             => $user_id,
                      'draft_id'            => $draft_id,
                     ));


                     if ($foto) {
                         $foto_list = [];
                         foreach ($foto as $key => $value) {
                             # code...
                             $extension = $value->getClientOriginalExtension();
                             if (in_array($extension, array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG'))){
                                 $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                                 $uploadedDest   = 'images/'.$informasi->_id;
                                 $uploadedName   = $random_name.'.'.$extension;

                                 $value->move($uploadedDest, $uploadedName);

                                 $foto_list[]   = $uploadedDest.'/'.$uploadedName;
                             }
                         }
                         $informasi->foto   = $foto_list;
                         $informasi->save();
                     }
                     if ($proposal) {
                         $extension  = $proposal->getClientOriginalExtension();
                         if (in_array($extension,  array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                             $uploadedDest   = 'docs/'.$informasi->_id;
                             $uploadedName   = 'proposal.'.$extension;

                             $proposal->move($uploadedDest, $uploadedName);
                             $informasi->proposal   = $uploadedDest.'/'.$uploadedName;
                             $informasi->save();
                         }
                     }
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

      $ikp_konten               = (isset($input['ikp_konten']))             ? $input['ikp_konten']             : null;
      $ikp_jumlah               = (isset($input['ikp_jumlah']))             ? $input['ikp_jumlah']             : null;
      $ikp_unit                 = (isset($input['ikp_unit']))               ? $input['ikp_unit']               : null;
      $ikp_verifikasi           = (isset($input['ikp_verifikasi']))         ? $input['ikp_verifikasi']         : null;
      $ikp_verket               = (isset($input['ikp_verket']))             ? $input['ikp_verket']             : null;

      $anggaran                 = (isset($input['anggaran']))               ? $input['anggaran']                : null;
      $anggaran_verifikasi      = (isset($input['anggaran_verifikasi']))    ? $input['anggaran_verifikasi']     : null;
      $anggaran_verket          = (isset($input['anggaran_verket']))        ? $input['anggaran_verket']         : null;
      $sumberanggaran           = (isset($input['sumberanggaran']))         ? $input['sumberanggaran']          : null;

      $jeniskegiatan            = (isset($input['jeniskegiatan']))          ? $input['jeniskegiatan']           : null;
      $keterangan               = (isset($input['keterangan']))             ? $input['keterangan']              : null;

      $foto_verifikasi          = (isset($input['foto_verifikasi']))        ? $input['foto_verifikasi']         : null;
      $foto_verket              = (isset($input['foto_verket']))            ? $input['foto_verket']             : null;

      $proposal_verifikasi      = (isset($input['proposal_verifikasi']))    ? $input['proposal_verifikasi']     : null;
      $proposal_verket          = (isset($input['proposal_verket']))        ? $input['proposal_verket']         : null;

      $foto                     = ($request->hasFile('foto'))               ? $request->file('foto')            : null;
      $proposal                 = ($request->hasFile('proposal'))           ? $request->file('proposal')        : null;

      if (!$isError) {
          try {
              $informasi    = Information::find($id);

              if ($informasi) {
                //   if (isset($konten) && $konten !== '') {
                //       $editedParams[]       = "konten";
                //       $informasi->push('archive_informasi',array('konten' => $informasi->konten, 'time' => \date("Y-m-d H:i:s")));
                //       $informasi->konten      = $konten;
                //   }
                  if (isset($ikp_konten) && $ikp_konten !== '') {
                      $editedParams[]       = "ikp_konten";
                      $informasi->ikp_konten   = $ikp_konten;
                  }
                  if (isset($ikp_jumlah) && $ikp_jumlah !== '') {
                      $editedParams[]       = "ikp_jumlah";
                      $informasi->ikp_jumlah   = $ikp_jumlah;
                  }
                  if (isset($ikp_unit) && $ikp_unit !== '') {
                      $editedParams[]       = "ikp_unit";
                      $informasi->ikp_unit   = $ikp_unit;
                  }
                  if (isset($ikp_verifikasi) && $ikp_verifikasi !== '') {
                      $editedParams[]       = "ikp_verifikasi";
                      $informasi->ikp_verifikasi   = $ikp_verifikasi;
                  }
                  if (isset($ikp_verket) && $ikp_verket !== '') {
                      $editedParams[]       = "ikp_verket";
                      $informasi->ikp_verket   = $ikp_verket;
                  }

                  if (isset($anggaran) && $anggaran !== '') {
                      $editedParams[]       = "anggaran";
                      $informasi->anggaran   = $anggaran;
                  }
                  if (isset($anggaran_verifikasi) && $anggaran_verifikasi !== '') {
                      $editedParams[]       = "anggaran_verifikasi";
                      $informasi->anggaran_verifikasi   = $anggaran_verifikasi;
                  }
                  if (isset($anggaran_verket) && $anggaran_verket !== '') {
                      $editedParams[]       = "anggaran_verket";
                      $informasi->anggaran_verket   = $anggaran_verket;
                  }

                  if (isset($sumberanggaran) && $sumberanggaran !== '') {
                      $editedParams[]       = "sumberanggaran";
                      $informasi->sumberanggaran   = $sumberanggaran;
                  }
                  if (isset($jeniskegiatan) && $jeniskegiatan !== '') {
                      $editedParams[]       = "jeniskegiatan";
                      $informasi->jeniskegiatan   = $jeniskegiatan;
                  }
                  if (isset($keterangan) && $keterangan !== '') {
                      $editedParams[]       = "keterangan";
                      $informasi->keterangan   = $keterangan;
                  }

                  if (isset($foto_verifikasi) && $foto_verifikasi !== '') {
                      $editedParams[]       = "foto_verifikasi";
                      $informasi->foto_verifikasi   = $foto_verifikasi;
                  }
                  if (isset($foto_verket) && $foto_verket !== '') {
                      $editedParams[]       = "foto_verket";
                      $informasi->foto_verket   = $foto_verket;
                  }

                  if (isset($proposal_verifikasi) && $proposal_verifikasi !== '') {
                      $editedParams[]       = "proposal_verifikasi";
                      $informasi->proposal_verifikasi   = $proposal_verifikasi;
                  }
                  if (isset($proposal_verket) && $proposal_verket !== '') {
                      $editedParams[]       = "proposal_verket";
                      $informasi->proposal_verket   = $proposal_verket;
                  }

                  if ($foto) {
                      $extension  = $foto->getClientOriginalExtension();
                      if (in_array($extension, array('jpg', 'jpeg','png', 'JPG', 'JPEG', 'PNG',))) {
                          $editedParams[] = "foto";
                          if (file_exists($informasi->foto)) { unlink($informasi->foto); }

                          $uploadedDest   = 'images/'.$id;
                          $uploadedName   = 'foto.'.$extension;

                          $foto->move($uploadedDest, $uploadedName);
                          $informasi->foto   = $uploadedDest.'/'.$uploadedName;
                      }
                  }
                  if ($proposal) {
                      $extension  = $proposal->getClientOriginalExtension();
                      if (in_array($extension, array('pdf', 'doc','docx', 'PDF', 'DOC', 'DOCX',))) {
                          $editedParams[] = "proposal";
                          if (file_exists($informasi->proposal)) { unlink($informasi->proposal); }

                          $uploadedDest   = 'images/'.$id;
                          $uploadedName   = 'proposal.'.$extension;

                          $proposal->move($uploadedDest, $uploadedName);
                          $informasi->proposal   = $uploadedDest.'/'.$uploadedName;
                      }
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

  public function uploadFoto(Request $request) {
      $returnData         = array();
      $response           = "OK";
      $statusCode         = 200;
      $result             = null;
      $message            = "Menyunting foto informasi sukses.";
      $isError            = FALSE;
      $editedParams       = null;

      $input              = $request->all();
      $foto               = ($request->hasFile('foto'))               ? $request->file('foto')            : null;

      if (!$isError) {
          try {
              if ($foto) {
                  foreach ($foto as $key => $value) {
                      $extension  = $value->getClientOriginalExtension();
                      if (in_array($extension, array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG'))) {
                          $random_name    = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,51 ) ,1 ) .substr( \md5( \time() ), 1);

                          $uploadedDest   = 'images/'.'coba';
                          $uploadedName   = $random_name.$extension;

                          $value->move($uploadedDest, $uploadedName);
                      }
                  }
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
