<?php

namespace Supriadi\BelajarPhpMvc\Controller;

class HomeController
{
    function index(): void
    {
        $model = [
            'title' => 'Belajar PHP MVC',
            'content' => 'Selamat belajar PHP MVC'
        ];

        //echo "HomeController.index()";
        require __DIR__.'/../View/Home/index.php';
    }

    function hello(): void
    {
        echo "HomeController.hello()";
    }

    function world(): void
    {
        echo "HomeController.world()";
    }

    function about(): void
    {
        echo "Author: Supriadi Roadman";
    }
}