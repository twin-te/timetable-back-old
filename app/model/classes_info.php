<?php
/**
 * kdbのcsvファイルから授業の詳細を取得します。
 */
namespace App\model;

class classes_info
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

    /**
     * 指定した授業番号の授業詳細を取得
     *
     * @param $attribute [string] 属性フィルタ
     * 'xxx' : xxxの授業番号の詳細を返す
     *
     * @return array|null
     */
    public static function get($attribute)
    {
        //日本語を扱う設定にしないとバグる
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        $f = fopen(__DIR__ . '/kdb.csv', 'r');
        $all = [];
        while ($line = fgetcsv($f)) {
            $all[] = new Classes_info($line[0], $line[1], $line[5], $line[6], $line[7], $line[8]);
        }
        fclose($f);

        //すべての場合にのみ直接返す
        if ($attribute == "all") {
            return $all;
        } else { //要求された属性に応じてフィルタリングして返す
            return array_filter($all, function ($var) use ($attribute) {
                return $var->number == $attribute;
            });
        }
    }
}
