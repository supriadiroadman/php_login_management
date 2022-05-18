<?php

require_once __DIR__.'/../vendor/autoload.php';

use Supriadi\BelajarPhpMvc\App\Router;
use Supriadi\BelajarPhpMvc\Controller\HomeController;

Router::add('GET', '/', HomeController::class,'index');
Router::add('GET', '/hello', HomeController::class,'hello');
Router::add('GET', '/world', HomeController::class,'world');
Router::add('GET', '/about', HomeController::class,'about');

Router::run();