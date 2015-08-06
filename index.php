<?php
require_once __DIR__ . '/autoload.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', $path);
$ctrl = !empty($pathParts[3]) ? ucfirst($pathParts[3]) : 'Index';
$act = !empty($pathParts[4]) ? ucfirst($pathParts[4]) : 'Default';
$controllerClassName = 'Application\\Controllers\\' . $ctrl;

    $controller = new $controllerClassName;
    $method = 'action' . $act;
    $controller->$method();