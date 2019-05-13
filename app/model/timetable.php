<?php
/**
 * 時間割の1コマずつのオブジェクトを作ります。
 */

namespace App\model;

class timetable
{
    /**
     * @var [string] 授業番号
     */
    public $number;

    /**
     * @var [string] 講義名
     */
    public $name;

    /**
     * @var [string] 開講学期
     */
    public $season;
    /**
     * @var [string] 開講時間
     */
    public $time;
    /**
     * @var [string] 教室
     */
    public $classroom;
    /**
     * @var [string] 担当講師
     */
    public $teacher;

    private function __construct($number, $name, $season, $time, $classroom, $teacher)
    {
        $this->number = $number;
        $this->name = $name;
        $this->season = $season;
        $this->time = $time;
        $this->classroom = $classroom;
        $this->teacher = $teacher;
    }

    public static function make($number, $name, $season, $time, $classroom, $teacher)
    {
        $all[0] = new timetable($number, $name, $season, $time, $classroom, $teacher);
        return $all[0];
    }
}
