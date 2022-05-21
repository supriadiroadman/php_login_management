<?php

namespace Supriadi\BelajarPhpMvc\Controller;

use Supriadi\BelajarPhpMvc\App\View;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;
use Supriadi\BelajarPhpMvc\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function index(): void
    {
        $user = $this->sessionService->current();

        if ($user == null) {
            View::render('Home/index', ['title' => 'PHP Login Management']);
        } else {
            View::render('Home/dashboard', [
                'title' => 'PHP Login Management',
                'user' => [
                    'name'=> $user->getName()
                ]
            ]);
        }
    }
}