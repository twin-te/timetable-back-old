<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\model\search;

class CoursesController extends Controller
{
    //


    public function index(Request $request)
    {
        $time_list = new search($request->number, $request->view_season);
        return response()->json($time_list,200,[],JSON_UNESCAPED_UNICODE);
    }

}
