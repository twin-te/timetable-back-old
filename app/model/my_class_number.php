<?php
/**
 * 自分の履修授業をcsvファイルから取得してオブジェクト化します。
 * もしくは配列で送られてきた授業番号を一つ一つオブジェクトに変換します。
 */
namespace App\model;

class my_class_number
{
    /**
     * @var [string] 授業番号
     */
    public $number;


    private function __construct($number)
    {
        $this->number = $number;
    }

    public static function array_get($array_get_number)
    {
        $all = [];
        foreach ($array_get_number as $each_get_number) {
            //不正なcsv対策。授業番号以外を入れられたらスキップする(文字数のみをカウント)
            if (strlen($each_get_number) == 7) {
                $all[] = new my_class_number($each_get_number);
            } elseif ($each_get_number != null) {
                http_response_code(400);
                // nullが代入された時はスキップ
                exit('undefined_class_number(Array)');
            }
        }
        return $all;
    }

    public static function csv_get($FileName)
    {
        //日本語を扱う設定にしないとバグる
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        $f = fopen(__DIR__ . '/temp/'. $FileName .'.csv', 'r');
        $all = [];
        while ($line = fgetcsv($f)) {
            //不正なcsv対策。授業番号以外を入れられたらスキップする(文字数のみをカウント)
            if (strlen($line[0]) == 7) {
                $all[] = new my_class_number($line[0]);
            } else {
                //取得したcsvファイルを消去
                unlink(__DIR__ . '/temp/'. $FileName .'.csv');
                http_response_code(400);
                exit('undefined_class_number(CSV)');
            }
        }
        fclose($f);
        //取得したcsvファイルを消去
        unlink(__DIR__ . '/temp/'. $FileName .'.csv');
         
        return $all;
    }
}
