<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\model\search;

class AuthCoursesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //DBのcourse_numberカラムのデータを表示して時間割データを作成
    public function show_course(Request $request)
    {
        $user = Auth::user();
        // jsonでDBに入っている授業番号を出力
        // return response($user -> course_number)->header('Content-Type', 'application/json');
        if ($user -> course_number == null) {
            http_response_code(400);
            return "no_data";
        }
        $time_list = new search(json_decode($user -> course_number)->number, $request->view_season);
        return response()->json($time_list, 200, [], JSON_UNESCAPED_UNICODE);
    }
 
    //DBのcourse_numberカラムのデータを更新
    public function update(Request $request)
    {
        $user = Auth::user();
        $user -> course_number = json_encode($request->all());
        $user -> save();
        return "save_OK";
    }

    //DBのdetailカラムのデータを表示
    public function show_detail()
    {
        $user = Auth::user();
        if ($user -> detail == null) {
            http_response_code(400);
            return "no_data";
        }
        return $user -> detail;
    }


    //DBのdetailカラムのデータを更新
    public function upload_detail(Request $request)
    {
        $user = Auth::user();
        $user -> detail = json_encode($request->all());
        $user -> save();
        return "save_OK";
    }
}
