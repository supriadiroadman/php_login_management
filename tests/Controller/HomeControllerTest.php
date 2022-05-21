<?php

namespace Supriadi\BelajarPhpMvc\Controller;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\Session;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;
use Supriadi\BelajarPhpMvc\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword('rahasia');
        $this->userRepository->save($user);

        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId($user->getId());
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Adi]");
    }


}
