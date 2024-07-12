<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    // index

    public function report_index()
    {
        $data = array();
        return view('backend.reports.assets')->with($data);
    }
}
