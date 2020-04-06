<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DokterController extends Controller
{
    public function __construct(){
        $this->host = env('MEDIGO', 'http://localhost:3001/');
    }

    public function index(Request $request) {
        return $this->view([
            'kelamin' => $this->getjeniskelamin(),
            'agama' => $this->getReligion(),
        ]);
    }

    public function store(Request $request){
        $data = [
            'no_induk'                   => $request->no_induk,
            'nama'                  => $request->nama,
            'jenkel'         => $request->jenkel,
            'spesialis'          => $request->spesialis,
            'skill'                 => $request->skill,
            'lokasi'         => $request->lokasi,
            'tgl_lagir'                => $request->tgl_lagir
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."create-dokter",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            return response()->json(json_decode($response), $statusCode);
        }
        curl_close($curl);
    }

    public function update(Request $request, $id) {
        $data = [
            'no_induk'                   => $request->no_induk,
            'nama'                  => $request->nama,
            'jenkel'         => $request->jenkel,
            'spesialis'          => $request->spesialis,
            'skill'                 => $request->skill,
            'lokasi'         => $request->lokasi,
            'tgl_lagir'                => $request->tgl_lagir
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."update-dokter/",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "PUT",
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            return response()->json(json_decode($response), $statusCode);
        }
        curl_close($curl);
    }

    public function delete($id){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."delete-dokter/" . $id,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "DELETE",
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            return response()->json(json_decode($response), $statusCode);
        }
        curl_close($curl);
    }

    public function show(){
        return $this->view(['data' => $this->getDokter()]);
    }

    private function getDokter() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."list-dokter/",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if( !$err ){
            $dataDokter = json_decode($response)->data;
        } else {
            $dataDokter = "Error";
        }

        return $dataDokter;
    }

    public function spesialis(Request $request){
        $dataReq = $dataReq = [
            'search'    => $request->q,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host . "list-spesialis",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($dataReq),
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            if ($statusCode != 200) {
                return response()->json(json_decode($response), $statusCode);
            } else {
                $res = json_decode($response, true);
                return response()->json($res['data'], $statusCode);
            }
        }
        curl_close($curl);
    }

    public function keahlian(Request $request){
        $dataReq = $dataReq = [
            'search'    => $request->q,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host . "list-skill",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($dataReq),
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            if ($statusCode != 200) {
                return response()->json(json_decode($response), $statusCode);
            } else {
                $res = json_decode($response, true);
                return response()->json($res['data'], $statusCode);
            }
        }
        curl_close($curl);
    }

    public function rumkit(Request $request){
        $dataReq = $dataReq = [
            'search'    => $request->q,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host . "list-rumkit",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($dataReq),
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        if ($err) {
            return $err;
        } else {
            $info       = curl_getinfo($curl);
            $statusCode = $info["http_code"];

            if ($statusCode != 200) {
                return response()->json(json_decode($response), $statusCode);
            } else {
                $res = json_decode($response, true);
                return response()->json($res['data'], $statusCode);
            }
        }
        curl_close($curl);
    }

    private function getjeniskelamin()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->kelamin . "list",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        if (!$err) {
            return json_decode($response)->data;
        } else {
            return $err;
        }
        curl_close($curl);
    }

    private function getReligion()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->agama . "list",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 50000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER      => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        if (!$err) {
            return json_decode($response)->data;
        } else {
            return $err;
        }
        curl_close($curl);
    }
}
