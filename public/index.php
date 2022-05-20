<?php

require_once __DIR__.'/../vendor/autoload.php';

use Supriadi\BelajarPhpMvc\App\Router;
use Supriadi\BelajarPhpMvc\Controller\HomeController;

Router::add('GET', '/', HomeController::class,'index', []);

Router::run();