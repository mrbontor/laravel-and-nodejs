<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $data['title'] = 'Medigo';
        return view('dashboard')->with($data);
    }
}
