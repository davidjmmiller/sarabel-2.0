<?php
session_start();

// Core Informative
class Core {
    public static $version = '2.0.0';
}

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
define('PATH_BLK', PATH_APP.'blk/');
define('PATH_LAN', PATH_APP.'lan/');
define('PATH_MDW', PATH_APP.'mdw/');

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

// Loading middleware
require PATH_CNF.'middleware.cnf.php';

// Detecting language
if (!isset($_SESSION['lang'])){
    $_SESSION['lang'] = $config['default_lang'];
}

$parameter = 'lang';
if (isset($_GET[$parameter])){
    switch($_GET[$parameter])
    {
        case 'en':
        case 'es':
            $_SESSION['lang'] = $_GET[$parameter];
            break;
        default:
            $_SESSION['lang'] = $config['default_lang'];
            break;
    }
}

// Loading language file
require PATH_LAN.$_SESSION['lang'].'.lan.php';

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$controller_name = ($request_uri[0] == '/' || $request_uri[0] == '' ? '/index': $request_uri[0]);

// Remove the / at the beginning and end
$controller_name = substr($controller_name,1);

if (substr($controller_name,-1) == '/') {
    $controller_name = substr($controller_name,0, -1);
}

$controller_path = PATH_CTL.$controller_name.'.ctl.php';
$controller_name = (file_exists($controller_path) ? $controller_name : '404');
$controller_path = PATH_CTL.$controller_name.'.ctl.php';

// Defaults
$layout_name = 'default';

// Start output capture
ob_start();

// Loading controller
require $controller_path;

// Loading view
$view = PATH_TPL.$controller_name.'.tpl.php';

if (file_exists($view)) {
    require $view;
}

$content = ob_get_contents();
ob_end_clean();

$layout_path = PATH_LAY.$layout_name.'.tpl.php';
$layout_path_ctl = PATH_LAY.$layout_name.'.ctl.php';

// Loading layout
if ($layout_name != '' && file_exists($layout_path) && file_exists($layout_path_ctl)) {

    // Loading layout controller
    require $layout_path_ctl;

    // Loading blocks
    if (isset($load_blocks) && is_array($load_blocks) && count($load_blocks) >0){
        foreach($load_blocks as $block){
            ob_start();
            $block_path = PATH_BLK.$block.'.blk.php';
            if (file_exists($block_path)){
                require $block_path;
            }
            $block_varname = str_replace(array('/','-',' '),'_',$block);
            $$block_varname = ob_get_contents();
            ob_end_clean();
        }
    }
    require($layout_path);
}
else {
    echo $content;
}