<?php

require_once __DIR__.'/../vendor/autoload.php';

use Supriadi\BelajarPhpMvc\App\Router;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Controller\HomeController;
use Supriadi\BelajarPhpMvc\Controller\UserController;
use Supriadi\BelajarPhpMvc\Middleware\MustLoginMiddleware;
use Supriadi\BelajarPhpMvc\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');

//HomeController
Router::add('GET', '/', HomeController::class,'index', []);
//UserController
Router::add('GET', '/users/register', UserController::class,'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class,'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class,'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class,'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class,'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class,'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class,'postUpdateProfile', [MustLoginMiddleware::class]);
Router::run();