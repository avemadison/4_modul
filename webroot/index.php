<?php
/**
 * home file /
 * here we have only App::run /
 * define DS /
 * define ROOT /
 * define VIEW_PATH /
 */

// set separator for directory separation
define('DS',DIRECTORY_SEPARATOR);

// ROOT указывает на 2 уровня высше чем index.php, то есть на 08_4_Modul, он нужен для включения файлов
define('ROOT',dirname(dirname(__FILE__)));

// constant for the path to the views
define('VIEWS_PATH',ROOT.DS.'views');

// connect init.php with __autoload() action
require_once (ROOT.DS.'lib'.DS.'init.php');

// REQUEST_URI получает строку запроса каторый пришел на index.php
// $router = new Router($_SERVER['REQUEST_URI']);

// old test code
/*
echo "<pre>";
print_r('Route: '      . $router->getRouter() . PHP_EOL);
print_r('Language: '   . $router->getLanguage() . PHP_EOL);
print_r('Controller: ' . $router->getController() . PHP_EOL);
print_r('Action: '     . $router->getMethodPrefix() . $router->getAction() . PHP_EOL);
echo "Params";
print_r($router->getParams());

try {
    App::run($_SERVER['REQUEST_URI']);
} catch ( Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
*/

// start session
session_start();

App::run($_SERVER['REQUEST_URI']);