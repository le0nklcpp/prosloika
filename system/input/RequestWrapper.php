<?php
namespace system\input;
/**
 * Request wrapper class
 * When constructed, automatically loads user input and some server variables
 * This class will be passed as a parameter to controller method
 * Class fields will contain user request info
 **/
class RequestWrapper
{
    /**
     * User IP address
     **/
    public $ip;
    /**
     * POST query, array decoded from JSON.
     * If you don't need to decode POST, read $_POST instead
     **/
    public $body;
    /**
     * $_GET
     **/
    public $get;
    /**
     * Query string parameters
     **/
    public $query;
    /**
     * Request method
     * 'GET','POST','PUT','PATCH','OPTIONS','DELETE',etc
     *  @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
     **/
    public $method;
    /**
     * Request path
     * for example: if a user sends a request with the following URI: https://127.0.0.1/v1/test $this->uri value will be set to v1/test
     **/
    public $uri;
    /**
     * Request headers
     **/
    public $headers;
    public function getParam(string $key,$a)
    {
        if(!is_array($a)||!array_key_exists($key,$a))return null;
        return $a[$key];
    }
    public function getHeader(string $key)
    {
        return $this->getParam($key,$this->headers);
    }
    public function getBodyParam(string $key)
    {
        return $this->getParam($key,$this->body);
    }
    public function getQueryParam(string $key)
    {
        return $this->getParam($key,$this->query);
    }
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        parse_str(parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY),$this->query);
        $this->headers = getallheaders();
        if($this->getHeader('Content-Type')=='multipart/form-data')
        {
            $this->body = $_POST;
        }
        else $this->body = json_decode(file_get_contents('php://input'),true);
        $this->get = $_GET;
        $this->method = $_SERVER['REQUEST_METHOD'];
    }
}
