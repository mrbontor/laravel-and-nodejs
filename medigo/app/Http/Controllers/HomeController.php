<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $data['title'] = 'Medigo';
        return view('home.index')->with($data);
        // return $this->view('home');
    }
}
