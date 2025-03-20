<?php
namespace app\controllers;
/*
 * Please note that in case of APCu installed this class will be initialized only once and stored in memory
 * so storing variables inside of it may be not the best practice, local variables are okay though
**/
class CssController
{
     public static $allowed_cors="GET,OPTIONS";
     public static function checkAccess($request,string $callback):bool
     {
         return true;
     }
     public static function get($request)
     {
         return ['code'=>'200 OK','file'=>'css/mvp.css'];
     }
}
