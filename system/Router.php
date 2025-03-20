<?php
namespace system;
//use input\RequestWrapper;
/**
 * This class has important routing routines along with CORS support, error handling and access control(it comes with brute force delay protection)
 * WARNING: CORS is enabled from any site by default
 * Set class fields right there
 */
class Router
{
    /** array containing routes
     * ['uri':'user/auth','method':'post','class':'controllers\UserController','callback':'auth']
     * This will call controllers\UserController->auth($request) method
     * To allow CORS you must add the following item:
     * ['uri':'user/auth','method':'post','class':'controllers\UserController','callback':'options']
     * Don't forget to set up CORS inside of your UserController!
     */
    public $routes = [];
    /**
     * HTTP header
     */
    public $HTTP_VER = 'HTTP/2';
    /**
     * Allowed CORS sites. Override to list of allowed CORS sites. Add '*' item if you want to accept any CORS request
     */
    public $cors_origin = ['*'];
    /**
      * Allowed headers, override to set your headers.
      */
    public $allowed_headers = 'Content-Type, Authorization, Session';
    public function response($code,$message)
    {
        header('HTTP/'.$this->HTTP_VER.' '.$code);
        echo($message);
    }
    //
    public function sendFile(string $file,mixed $raw=null)
    {
        header('Content-Type:');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Transfer-Encoding: binary');
        if($raw)
        {
            header('Content-Length: ' . strlen($raw));
            echo $raw;
        }
        else
        {
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
    }
    public function preload()
    {
        foreach($this->routes as $iter)
        {
            $iter['class'] = new $iter['class'];
        }
    }
    public function process()
    {
        $request = new \system\input\RequestWrapper();
        syslog(LOG_INFO,'URI:'.$request->uri.' Method: '.$request->method);
        foreach($this->routes as $iter)
        {
            if($iter['uri']==$request->uri&&$iter['method']==$request->method)
            {
                $controller = $iter['class'];
                if($iter['uri']=='OPTIONS') // I hate CORS
                {
                    header('HTTP/'.$this->HTTP_VER.' 200 OK');
                    header('Allow:'.$controller::allowed_cors);
                    if((in_array('Origin',$request->headers)&&in_array($request->headers['Origin'],$this->cors_origin)))
                    {
                        header('Access-Control-Allow-Origin:'.$this->cors_origin[$request->headers['Origin']]);
                    }
                    else if(in_array('*',$this->cors_origin))header('Access-Control-Allow-Origin:*');
                    header('Access-Control-Allow-Methods:'.$controller::allowed_cors);
                    header('Access-Control-Allow-Headers:'.$this->allowed_headers);
                    return;
                }
                try{
                    if(!$controller::checkAccess($request,$iter['callback']))
                    {
                        header('HTTP/'.$this->HTTP_VER.' 401 Unauthorized',true);
                        sleep(4); // Brute-force protection
                        return;
                    }
                    $result = $controller::{$iter['callback']}($request);
                    if(!is_array($result))
                    {
                        $message = $result;
                        $result = ['message' => $result];
                    }
                    else if(!array_key_exists('message',$result))
                    {
                         header('Content-Type: application/json',true);
                         $result['message'] = json_encode($result,JSON_UNESCAPED_UNICODE);
                         if(!array_key_exists('code',$result))$result['code'] = '200 OK';
                    }
                    if(is_array($result['message']))
                    {
                        header('Content-Type: application/json',true);
                        $result['message'] = json_encode($result['message'],JSON_UNESCAPED_UNICODE);
                    }
                    $this->response($result['code'],$result['message']);
                    if(array_key_exists('file',$result))
                    {
                        $this->sendFile($result['file'],array_key_exists('raw',$result)?$result['raw']:null);
                    }
                }
                catch(Exception $e)
                {
                    http_response_code(500);
                    error_log(\system\input\escapeInput::escape($e->getMessage(),\system\input\escapeInput::ESCAPE_PHP));
                }
                return;
            }
        }
        header('HTTP/'.$this->HTTP_VER.' 404 Not found',true);
    }
}
