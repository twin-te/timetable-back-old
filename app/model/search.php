<?php
/**
 * 取得した授業の詳細情報を表示します。
 */

namespace App\model;

use \App\model\classes_info;
use \App\model\my_class_number;
use \App\model\timetable;

class search
{
    public $result;

    public function __construct($array_get_number, $view_season)
    {
        if ($view_season != null) {
            //学期ごとの処理
            switch ($view_season) {
        case "haruA":
        $season1 = "春";
        $season2 = "A";
        break;
        case "haruB":
        $season1 = "春";
        $season2 = "B";
        break;
        case "haruC":
        $season1 = "春";
        $season2 = "C";
        break;
        case "akiA":
        $season1 = "秋";
        $season2 = "A";
        break;
        case "akiB":
        $season1 = "秋";
        $season2 = "B";
        break;
        case "akiC":
        $season1 = "秋";
        $season2 = "C";
        break;
        default:
        http_response_code(400);
        exit('invalid_gakki_value');
    }
        } else {
            http_response_code(400);
            exit('invalid_gakki_value');
        }

        /**
         * 取得した授業の詳細情報を表示します。
         */


        //空きコマの処理
        for ($i = 0;$i < 6;$i++) {
            $Monday[$i] = timetable::make("", "", "", "", "", "");
            $Tuesday[$i] = timetable::make("", "", "", "", "", "");
            $Wednesday[$i] = timetable::make("", "", "", "", "", "");
            $Thursday[$i] = timetable::make("", "", "", "", "", "");
            $Friday[$i] = timetable::make("", "", "", "", "", "");
        }

        $course_list = my_class_number::array_get($array_get_number);


        foreach ($course_list as $list) {
            /*授業番号を指定してその授業の詳細を取得*/
            $number_list = classes_info::get($list->number);//授業番号を指定
            foreach ($number_list as $each_class_info) {
                $div_class_season = null;
                //開講が春B\n春C等になってる場合を考慮
                if (strpos($each_class_info->season, "\n") !== false) {
                    $div_class_season = explode("\n", $each_class_info->season);
                } else {
                    $div_class_season[0] = $each_class_info->season;
                }
                //dd($div_class_season);
                $m=0;
                $n=0;
                //とりあえず学期に分ける
                foreach ($div_class_season as $div_season) {
                    $m++;
                    $n=0;

                    //返す学期が指定されていた場合、返す学期に開講していない授業は無視される
                    if ($view_season == null || ($view_season != null && strpos($div_season, $season1) !== false && strpos($div_season, $season2) !== false)) {
                        $div_class_time = null;

                        //開講が水1・2,木3・4とかの場合を考慮（csvに改行コードがある）
                        if (strpos($each_class_info->time, "\n") !== false) {
                            $div_class_time = explode("\n", $each_class_info->time);
                        } else {
                            $div_class_time[0] = $each_class_info->time;
                        }

                        //var_dump($div_class_time);
                        //var_dump($each_class_info->name);
                        foreach ($div_class_time as $div_time) {
                            $n++;
                            //もし開講学期が複数（春ABとかでなく、春A\n春Bとなってる場合）
                            if (!(count($div_class_season) >1&& $n != $m)) {
                                // 火3-6など3コマ連続以上のものの考慮
                                if (preg_match('/(\w)-/', $div_time, $match)) {
                                    $start = $match[1];
                                    preg_match('/-(\w)/', $div_time, $match);
                                    $end = $match[1];
                                    for ($i = $start;$i<=$end;$i++) {
                                        $div_time .= ",".$i;
                                    }
                                }

                                for ($i = 0;$i < 6;$i++) {
                                    if (strpos($div_time, (String) ($i + 1)) !== false) {
                                        if (strpos($div_time, "月") !== false) {
                                            $Monday[$i] = timetable::make($each_class_info->number, $each_class_info->name, $each_class_info->season, $each_class_info->time, $each_class_info->classroom, $each_class_info->teacher);
                                        }
                                        if (strpos($div_time, "火") !== false) {
                                            $Tuesday[$i] = timetable::make($each_class_info->number, $each_class_info->name, $each_class_info->season, $each_class_info->time, $each_class_info->classroom, $each_class_info->teacher);
                                        }
                                        if (strpos($div_time, "水") !== false) {
                                            $Wednesday[$i] = timetable::make($each_class_info->number, $each_class_info->name, $each_class_info->season, $each_class_info->time, $each_class_info->classroom, $each_class_info->teacher);
                                        }
                                        if (strpos($div_time, "木") !== false) {
                                            $Thursday[$i] = timetable::make($each_class_info->number, $each_class_info->name, $each_class_info->season, $each_class_info->time, $each_class_info->classroom, $each_class_info->teacher);
                                        }
                                        if (strpos($div_time, "金") !== false) {
                                            $Friday[$i] = timetable::make($each_class_info->number, $each_class_info->name, $each_class_info->season, $each_class_info->time, $each_class_info->classroom, $each_class_info->teacher);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $time_list[] = (array)$Monday;
        $time_list[] = (array)$Tuesday;
        $time_list[] = (array)$Wednesday;
        $time_list[] = (array)$Thursday;
        $time_list[] = (array)$Friday;

        //var_dump($time_list);
        $this->result = $time_list;
    }
}
