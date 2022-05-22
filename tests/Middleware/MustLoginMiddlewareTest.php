<?php

namespace Supriadi\BelajarPhpMvc\App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace Supriadi\BelajarPhpMvc\Middleware {

    use PHPUnit\Framework\TestCase;
    use Supriadi\BelajarPhpMvc\Config\Database;
    use Supriadi\BelajarPhpMvc\Domain\Session;
    use Supriadi\BelajarPhpMvc\Domain\User;
    use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
    use Supriadi\BelajarPhpMvc\Repository\UserRepository;
    use Supriadi\BelajarPhpMvc\Service\SessionService;

    class MustLoginMiddlewareTest extends TestCase
    {
        private MustLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void
        {
            $this->middleware = new MustLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();
        }

        public function testBeforeGuest()
        {
            $this->middleware->before();
            $this->expectOutputRegex("[Location: /users/login]");
        }

        public function testBeforeLoginUser()
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

            $this->middleware->before();
            $this->expectOutputString("");
        }

    }
}
