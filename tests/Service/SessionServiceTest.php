<?php

namespace Supriadi\BelajarPhpMvc\Service;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\Session;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;

function setcookie(string $name, string $value)
{
    echo "$name: $value";
}

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword('rahasia');

        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create('adi');

        $sessionId = $session->getId();
        $this->expectOutputRegex("[X-SUPRIADI-SESSION: $sessionId]");

        $result = $this->sessionRepository->findById($session->getId());

        self::assertEquals($session->getUserId(), $result->getUserId());
    }

    public function testDestroy()
    {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId('adi');

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();
        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-SUPRIADI-SESSION: ]");

        $result = $this->sessionRepository->findById($session->getId());
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId('adi');

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

        $user = $this->sessionService->current();

        self::assertEquals($session->getUserId(), $user->getId());
    }
}