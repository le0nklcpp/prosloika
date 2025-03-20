<?php
namespace app\controllers;
use system\RestController;
use app\models\UserSession;
class SecureController extends RestController
{
    public $session;
    public function getSession($request)
    {
        if(array_key_exists('Token',$request->headers))
        {
            $smodel = new UserSession();
            return $smodel->findSession($request->headers['Token']);
        }
        return null;
    }
    public function checkAdminAccess($request)
    {
        if(!$this->session)$this->session = $this->getSession($request);
        if($this->session==null)return false;
        return $this->session->getUser()->role=='admin';
    }
    public function checkAccess($request,string $callback)
    {
        $this->session = $this->getSession($request);
        if($this->session!==null)return true;
        return false;
    }
}