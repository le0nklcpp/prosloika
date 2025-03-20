<?php
namespace app\controllers;
use app\models\UserModel;
use app\models\UserSession;
class UserController extends \system\Controller
{
    public static $allowed_cors = 'POST,OPTIONS';
    /*
     * Performs access check: in this case it should only allow requesting /user/auth
     **/
    public static function checkAccess($request,string $callback):bool
    {
        if($callback=='auth'||$callback=='ping')return true;
        return false;
    }
    /*
     * user/auth request
     * @param $request MicroMC request
     **/
    public static function auth($request)
    {
          $model = new UserModel($request->body);
          /*
           * Associative array we pass here is escaped, however any other form of arguments is NOT, so you should not do that
           **/
          $result = $model->select()->where(['login' => $model->login,'password' => $model->password])->one();
          if(!$result)
          {
              sleep(5); //' Brute-force protection
              return ['code' => '401 Unauthorized', 'message' => 'Authorization Required'];
          }
          $model->load($result);
          return ['code' => '200 OK','message'=>((new UserSession())->createSession($model,$request->ip))];
    }
    public static function ping($request)
    {
        return ['code'=>'200 OK','message'=>$request->body];
    }
}
