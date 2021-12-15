<?php
/**
 * Created by PhpStorm.
 * User: netpioneer
 * Date: 7/6/2019
 * Time: 12:43 PM
 */

namespace netpioneer\authplus\Classes;


class Captcha
{
    public static function GenerateCaptcha(){
        $string = '';
        for ($i = 0; $i < 6; $i++) {
            // this numbers refer to numbers of the ascii table (lower case)
            $string .= chr(rand(48, 57));
        }
        Session(['CAPTCHA'=>$string]);
        $string = preg_replace("[\D+]", "", $string);
        if(strlen($string) > 7){
            die("high num");
        }
        $dir = 'fonts/';
        $image = imagecreatetruecolor(210, 60);
        $black = imagecolorallocate($image, 0, 0, 0);
        $color = imagecolorallocate($image, 100, 100, 90);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image,0,0,399,99,$white);
        imagettftext ($image, 30, 0, 10, 40, $color,public_path(DIRECTORY_SEPARATOR) . "RAVIE.TTF", $string);
        header("Content-type: image/png");
        imagepng($image);
    }
    public static function check($input){
        $c = Session()->get('CAPTCHA');
        Session()->remove('CAPTCHA');
        return ($c == $input ? true : false);
    }
}
