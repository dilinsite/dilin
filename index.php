<?php 

define('APP_PATH', realpath(dirname(__FILE__)) . '/app');
define('APP_ENV', (getenv('APP_ENV') ? getenv('APPLICATION_ENV') : 'development'));

set_include_path(implode(PATH_SEPARATOR, array(realpath('/hermes/bosweb/web196/b1969/ipg.dilincom/ZendFramework-1.11.12/library'), realpath('F:\wamp\www\ZendFramework-1.12.0\library'), get_include_path())));

require_once 'Zend/Application.php';

$application = new Zend_Application(APP_ENV, APP_PATH . '/configs/app.ini');

$application->bootstrap()->run();
?>