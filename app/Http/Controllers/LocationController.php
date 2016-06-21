<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Location;

class LocationController extends Controller {
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
        $message            = "Mengambil semua data lokasi sukses.";
        $isError            = FALSE;
        $missingParams      = null;

        $input              = $request->all();
        $limit              = (isset($input['limit']))     ? $input['limit']    : null;
        $offset             = (isset($input['offset']))    ? $input['offset']   : null;

        if (!$isError) {
            try {
                $result = Location::take($limit)->skip($offset)->get();

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
   * Store a newly created resource in storage.
   *
   * @param  Request  $request
   * @return Response
   */
   public function store(Request $request){
     $returnData        = array();
     $response          = "OK";
     $statusCode        = 200;
     $result            = null;
     $message           = "Menyimpan data lokasi baru sukses";
     $isError           = FALSE;
     $missingParams     = null;

     $input             = $request->all();
     $detail            = (isset($input['detail']))       ? $input['detail']    : null;
     $dusun             = (isset($input['dusun']))        ? $dusun['dusun']     : null;
     $kelurahan         = (isset($input['kelurahan']))    ? $input['kelurahan'] : null;
     $kecamatan         = (isset($input['kecamatan']))    ? $input['kecamatan'] : null;

     if (!isset($kecamatan) || $kecamatan == '') {
         $missingParams[] = "kecamatan";
     }

     if (isset($missingParams)) {
         $isError    = TRUE;
         $response   = "FAILED";
         $statusCode = 400;
         $message    = "Missing parameters : {".implode(', ', $missingParams)."}";
     }

     if (!$isError) {
         try {
             $location = Location::create(array(
                 'detail'       => $detail,
                 'dusun'        => $dusun,
                 'kelurahan'    => $kelurahan,
                 'kecamatan'    => $kecamatan,
             ));

             $result['id']      = $location->_id;
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
       $message            = "Mengambil data lokasi dengan id $id sukses.";
       $isError            = FALSE;
       $missingParams      = null;

       if (!$isError) {
           try {
               $result = Location::where('_id', $id)->first();

               if (!$result) {
                   throw new \Exception("Lokasi dengan id $id tidak ditemukan.");
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
       $message            = "Menyunting data lokasi sukses.";
       $isError            = FALSE;
       $editedParams       = null;

       $input              = $request->all();
       $detail             = (isset($input['detail']))       ? $input['detail']    : null;
       $dusun              = (isset($input['dusun']))        ? $input['dusun']     : null;
       $kelurahan          = (isset($input['kelurahan']))    ? $input['kelurahan'] : null;
       $kecamatan          = (isset($input['kecamatan']))    ? $input['kecamatan'] : null;

       if (!$isError) {
           try {
               $location      = Location::find($id);

               if ($location) {
                   if (isset($detail) && $detail !== '') {
                       $editedParams[]         = "detail";
                       $location->detail       = $detail;
                   }
                   if (isset($dusun) && $dusun !== '') {
                       $editedParams[]         = "dusun";
                       $location->dusun        = $dusun;
                   }
                   if (isset($kelurahan) && $kelurahan !== '') {
                       $editedParams[]         = "kelurahan";
                       $location->kelurahan    = $kelurahan;
                   }
                   if (isset($kecamatan) && $kecamatan !== '') {
                       $editedParams[]         = "kecamatan";
                       $location->kecamatan       = $kecamatan;
                   }

                   if (isset($editedParams)) {
                       $location->save();

                       $message    = $message." Data yang berubah : {".implode(', ', $editedParams)."}";
                   } else {
                       $message    = $message." Tidak ada data yang berubah.";
                   }
               } else {
                   throw new \Exception("Lokasi dengan id $id tidak ditemukan.");
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
       $message            = "Menghapus data lokasi dengan $id sukses.";
       $isError            = FALSE;
       $missingParams      = null;

       if (!$isError) {
           try {
               $location      = Location::find($id);

               if ($location) {
                   $location->delete();
               } else {
                   throw new \Exception("Lokasi dengan id $id tidak ditemukan.");
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
