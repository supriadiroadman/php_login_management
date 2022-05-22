<?php

namespace Supriadi\BelajarPhpMvc\Middleware;

use Supriadi\BelajarPhpMvc\App\View;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;
use Supriadi\BelajarPhpMvc\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $userRepository = new UserRepository(Database::getConnection());
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            View::redirect('/');
        }
    }
}