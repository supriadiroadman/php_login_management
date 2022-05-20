<?php

namespace Supriadi\BelajarPhpMvc\Controller;

use Supriadi\BelajarPhpMvc\App\View;

class HomeController
{
    function index(): void
    {
        View::render('Home/index', ['title' => 'PHP Login Management']);
    }
}