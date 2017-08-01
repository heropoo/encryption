<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2017/8/1
 * Time: 11:41
 */

require '../vendor/autoload.php';

$origin_str = 'I love you!';

$key = 'fsdafeFEWF$$*&()FSD34fst/fsd';  //Only keys of sizes 16, 24 or 32 supported

$aes = new \Moon\Aes($key);

echo 'origin_str:  '.$origin_str.'<br>';

$encode_str = $aes->encrypt($origin_str);

echo 'encode_str:  '.$encode_str.'<br>';

$decode_str = $aes->decrypt($encode_str);

echo 'encode_str:  '.$decode_str.'<br>';

