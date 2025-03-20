<?php
namespace app\config;
class Routes
{
/*
 * This is where all the routes stored in the following format:
 * ['uri':'user/auth','method':'post','class':'controllers\UserController','callback':'auth']
 * This will call controllers\UserController->auth($request) method
 *  To allow CORS you must add the following item:
 * ['uri':'user/auth','method':'OPTIONS','class':'controllers\UserController','callback':'options']
 * Don't forget to set up CORS inside of your UserController!
* */
public static $routes = [
['uri'=>'/user/auth','method'=>'POST','class'=>'\app\controllers\UserController','callback'=>'auth'],
['uri'=>'/css','method'=>'GET','class'=>'\app\controllers\CssController','callback'=>'get'],
['uri'=>'/ping','method'=>'POST','class'=>'\app\controllers\UserController','callback'=>'ping']
];
}
