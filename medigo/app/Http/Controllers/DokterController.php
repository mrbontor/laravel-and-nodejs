<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function __construct(){
        $this->host = env('DOKTER', 'http://localhost:3001/');
    }

    public function index() {
        $data['title'] = 'Medigo';
        return view('dokter.index')->with($data);
    }

    public function booking() {
        $data['title'] = 'Medigo';
        return view('dokter.booking')->with($data);
    }

    public function search() {
        $data['title'] = 'Medigo';
        return view('dokter.search')->with($data);
    }

    public function list() {
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."list-dokter",
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
            return $output = json_decode($response)->data;
        }

        return $output;
    }

    public function store(Request $request){
        $data = [
            'no_induk'              => $request->nip,
            'nama'                  => $request->nama,
            'jenkel'                => $request->jenkel,
            'spesialis'             => $request->spesialis,
            'lokasi'                => $request->lokasi,
            'skill'                 => $request->skill,
            'tgl_lahir'             => $request->tgl_lahir,
            'keterangan'            => $request->keterangan
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

    public function update(Request $request) {
        $data = [
            'no_induk'              => $request->nip,
            'nama'                  => $request->nama,
            'jenkel'                => $request->jenkel,
            'spesialis'             => $request->spesialis,
            'lokasi'                => $request->lokasi,
            'skill'                 => $request->skill,
            'tgl_lahir'             => $request->tgl_lahir,
            'keterangan'            => $request->keterangan,
            'status'                => $request->status
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."update-dokter",
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

    public function destroy($id){
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

    public function show($id){
        $data['title'] = 'Detail Dokter';
        $data['dokter'] = $this->get_dokter($id);

        // dd($data['dokter']);
        return view('dokter.detail')->with($data);
    }

    public function get_dokter($id) {
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."find-dokter/" . $id,
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

            return $output = json_decode($response, JSON_PRETTY_PRINT);
        }
        return $output;
    }

    public function search_autocomplete(Request $request) {
        $output = [];
        if($request->get('q'))
        {
            $q = $request->get('q');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->host ."search-dokter/" . $q,
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
                // dd($response);
                // if (sizeof(json_decode($response)->data) > 0) {
                //     $output =
                // }
                return $output = json_encode($response);
            }
        }

        return $output;
    }

    public function search_by_spesialis($id) {
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."find-by-spesialis/" . $id,
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
            return $output = json_encode($response);
        }
        return $output;
    }

    public function search_by_spesialis_lokasi(Request $request) {
        $output = [];
        $data = [
            'lokasi'                => $request->lokasi,
            'spesialis'             => $request->spesialis
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."find-by-speslok",
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

        if( !$err ){
            return $output = json_encode($response);
        }
        return $output;
    }

    public function search_by_lokasi(Request $request) {
        $data = [
            'nama'                  => $request->nama,
            'lokasi'             => $request->lokasi
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."find-by-lokasi",
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

        if( !$err ){
            return json_encode($response);
        }else {
            return $err;
        }
    }

    public function search_by_skill($id) {
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."find-by-skill/" . $id,
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
            return $output = json_encode($response);
        }
        return $output;
    }

    public function spesialis(Request $request){
        $output = [];
        if($request->get('q'))
        {
            $q = $request->get('q');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->host ."search-spesialis/" . $q,
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

                return $output = json_encode($response);
            }
        }
        return $output;
    }

    public function keahlian(Request $request){
        $output = [];
        if($request->get('q'))
        {
            $q = $request->get('q');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->host ."search-skill/" . $q,
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

                return $output = json_encode($response);
            }
        }
        return $output;
    }

    public function rumkit(Request $request){
        $output = [];
        if($request->get('q'))
        {
            $q = $request->get('q');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->host ."search-rumkit/" . $q,
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
                return $output = json_encode($response);
            }
        }
        return $output;
    }

    public function rumkit_list(Request $request){
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."list-rumkit",
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
            return $output = json_encode($response);
        }
    }

    public function spesialis_list(Request $request){
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."list-spesialis",
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
            return $output = json_encode($response);
        }
    }

    public function skill_list(Request $request){
        $output = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->host ."list-skill",
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
            return $output = json_encode($response);
        }
        return $output;
    }
}
