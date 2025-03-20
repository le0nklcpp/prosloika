<?php
namespace system;
/*
 * Please note that in case of APCu installed this class will be initialized only once and stored in memory
 * so storing variables inside of it may be not the best practice, local variables are okay though
 */
class Controller
{
     /*
      * List of allowed CORS Methods, that will be sent on an OPTIONS request
      * i.e. $allowed_cors='GET,POST,PUT,PATCH' will allow performing
      * any type of REST queries
      * Note that you should also enable it inside of @link file://../app/config/routes.php 
      */
     public static $allowed_cors="";
     /**
      * Performs the access check before any other code from this controller is executed
      * Use it to perform authorization token check, requested data access check, etc.
      * @param $request is RequestWrapper class from @link file://input/RequestWrapper.php
      * @param $callback is the controller method that will be called. See @link file://../config/routes.php
      * @return true if a request passed access check,
      * false if you decide that this request should not be performed inside of this controller
      * i.e. if you want to restrict access without the bearer token:
      * 
      * if($request->headers['Authorization']!='Onyetwewewe Uvuwewewe Ugbemugbem Osas')return false;
      * 
      * to allow connections only from localhost use:
      * 
      * if($request->ip!='127.0.0.1')return false;
      * else return true;
      *
      * Best moment to check if a user has an access to the requested method:
      * if($request->uri=='/user/create'&&(performYourCheck($request->headers['session-token'])===true))return true;
      * or better use 
      * if($callback=='methodCreate'&&(performYourCheck($request->headers['session-token'])===true))return true;
      *
      * Right now it is a dummy function that will always return false. Don't forget to override it!
      */
     public static function checkAccess($request,string $callback):bool
     {
         $session = $request->headers['Token']; 
         return false;
     }
     /**
      * Those are dummy functions as an example. You are not ought to override them.
      * But that certainly will appear more RESTful
      * @return array('code' => http code and status in one line i.e. '200 OK' or '401 Unauthorized',
      * 'message' => actual message to display. If an array passed, the message will be sent as JSON response
      * )
      */
     public static function get($request)
     {
     }
     /**
      * Those are dummy functions as an example. You are not ought to override them.
      * But that certainly will appear more RESTful
      * @return array('code' => http code and status in one line i.e. '200 OK' or '401 Unauthorized',
      * 'message' => actual message to display. If an array passed, the message will be sent as JSON response
      * )
      */
     public static function view($request)
     {
     }
     /**
      * Those are dummy functions as an example. You are not ought to override them.
      * But that certainly will appear more RESTful
      * @return array('code' => http code and status in one line i.e. '200 OK' or '401 Unauthorized',
      * 'message' => actual message to display. If an array passed, the message will be sent as JSON response
      * )
      */
     public static function put($request)
     {
     }
     /**
      * Those are dummy functions as an example. You are not ought to override them.
      * But that certainly will appear more RESTful
      * @return array('code' => http code and status in one line i.e. '200 OK' or '401 Unauthorized',
      * 'message' => actual message to display. If an array passed, the message will be sent as JSON response
      * )
      */
     public static function post($request)
     {
     }
     /**
      * Those are dummy functions as an example. You are not ought to override them.
      * But that certainly will appear more RESTful
      * @return array('code' => http code and status in one line i.e. '200 OK' or '401 Unauthorized',
      * 'message' => actual message to display. If an array passed, the message will be sent as JSON response
      * )
      */
     public static function patch($request)
     {
     }
}
