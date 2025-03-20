<?php
use app\config\DBSettings;
use app\config\Routes;
//spl_autoload_extensions('.php');
//$time = microtime(true);
spl_autoload_register(function ($ob){
    $path = str_replace('\\','/',$ob);
    $path .= '.php';
    if(file_exists($path))
    {
        include($path);
    }
    });
$router = NULL;
// Uncomment &&0 in a line below to disable APCU caching
if(function_exists('apcu_enabled')/*&&0*/)
{
    if(!apcu_exists('router'))
    {
        DBSettings::init_connections();
        apcu_add('dbs',DBSettings::$dbs);
        include('app/config/routes.php');
        $router = new system\Router();
        $router->routes = Routes::$routes;
        $router->preload();
        apcu_add('router',$router);
    }
    else 
    {
        $router = apcu_fetch('router');
        DBSettings::$dbs = apcu_fetch('dbs');
    }
}
else
{
    app\config\DBSettings::init_connections();
    $router = new system\Router();
    include('app/config/routes.php');
    $router->routes = Routes::$routes;
}
$router->HTTP_VER = $_SERVER['SERVER_PROTOCOL'];
$router->process();
//$time = microtime(true)-$time;
//header('Processing-time: '.$time.' us');
