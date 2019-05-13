<?php
/**
 * リキャプチャの設定
 */
namespace App\model;

class recaptcha
{
    /**
     * @var [bool] 認証成功かどうか
     */
    public $success;


    public function __construct($token)
    {
                //your site secret key
                $secret = '6LeHKp8UAAAAAAYxdDRRDX-lNxWfFGmjOLC4KL74';
                //get verify response data
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$token);
                $responseData = json_decode($verifyResponse);
                $this->success = $responseData->success;
                if (!$responseData->success) {
                    http_response_code(400);
                    exit("You_are_a_robot");
                }
    }
}