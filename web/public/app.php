<?php
$dir = dirname(__FILE__);
include './loginCheck.php';
require_once realpath($dir."/../../lib/Nora/script/bootstrap.php");

ini_set('display_errors',1);
error_reporting(E_ALL);

define('YAMAHIRO_SYSTEM_DIR',realpath($dir."/../../system"));
define('YAMAHIRO_MOVING_AVERAGE_DAY_DIR',YAMAHIRO_SYSTEM_DIR."/KOUBAI/DATA/APP/MOVING_AVERAGE_DAYLY");
define('YAMAHIRO_MOVING_AVERAGE_MONTH_DIR',YAMAHIRO_SYSTEM_DIR."/KOUBAI/DATA/APP/MOVING_AVERAGE_MONTHLY");


// アプリケーションを起動
$app = Nora\App\App::createApp( $dir."/../app" );

// リクエストを起動
$request = new Nora\Request\WebRequest();
//$request->setRequestURI('/getMobingAverage/ss/0001/item/011000/mode/daily');

// ルータを起動
$router = new Nora\Router\UriRouter( );
$router->addRoute('/csv/getMonthly',array(
    'module'=>'app',
    'controller'=>'csv',
    'action'=>'getMonthly'
), 10);
$router->addRoute('/csv/getDaily',array(
    'module'=>'app',
    'controller'=>'csv',
    'action'=>'getDaily'
), 10);
$router->route( $request );


// アプリケーションにレスポンスをリクエストする
try {
    $response = $app->request( $request );
} catch(Exception $e) {
    echo $e;
}

echo $response->toString();
