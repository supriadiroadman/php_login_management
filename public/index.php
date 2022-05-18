<?php

require_once __DIR__.'/../vendor/autoload.php';

use Supriadi\BelajarPhpMvc\App\Router;

Router::add('GET', '/', 'HomeController','index');
Router::add('GET', '/hello', 'HelloController','hello');
Router::add('GET', '/world', 'HelloController','world');

Router::run();