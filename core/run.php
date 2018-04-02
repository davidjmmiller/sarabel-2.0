<?php

// Define paths
define('PATH_APP', '../'.APP_NAME.'/');
define('PATH_CORE', '../core/');
define('PATH_CORE_LIB', PATH_CORE.'lib/');
define('PATH_CNF', PATH_APP.'cnf/');
define('PATH_CTL', PATH_APP.'ctl/');
define('PATH_MDL', PATH_APP.'mdl/');
define('PATH_LIB', PATH_APP.'lib/');
define('PATH_TPL', PATH_APP.'tpl/');
define('PATH_LAY', PATH_APP.'lay/');

// Loading config file
require PATH_CNF.'global.cnf.php';
require PATH_CNF.'database.cnf.php';
require PATH_CNF.'email.cnf.php';
require PATH_CNF.'libraries.cnf.php';

// Loading core libraries
require PATH_CORE_LIB.'utils.lib.php';
require PATH_CORE_LIB.'database.lib.php';
require PATH_CORE_LIB.'form.lib.php';
require PATH_CORE_LIB.'mail.lib.php';


$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$controller_name = ($request_uri[0] == '/' || $request_uri[0] == '' ? '/index': $request_uri[0]);

$controller_name = substr($controller_name,1);
$controller_path = PATH_CTL.$controller_name.'.ctl.php';
$controller_name = (file_exists($controller_path) ? $controller_name : '404');
$controller_path = PATH_CTL.$controller_name.'.ctl.php';

// Start output capture
ob_start();

// Loading controller
require ($controller_path);

// Loading view
$view = PATH_TPL.$controller_name.'.tpl.php';

if (file_exists($view)) {
    require($view);
}

$content = ob_get_contents();
ob_end_clean();

// Loading layout
require (PATH_LAY.'default.tpl.php');
