<?php

namespace Supriadi\BelajarPhpMvc\Repository;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\Session;
use Supriadi\BelajarPhpMvc\Domain\User;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword('rahasia');

        $this->userRepository->save($user);
    }

    public function testSaveSuccess()
    {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId('adi');
        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->getId());

        self::assertEquals($session->getId(), $result->getId());
        self::assertEquals($session->getUserId(), $result->getUserId());
    }

    public function testDeleteByIdSuccess()
    {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId('adi');
        $this->sessionRepository->save($session);

        $this->sessionRepository->deleteById($session->getId());

        $result = $this->sessionRepository->findById($session->getId());
        self::assertNull($result);
    }

    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById('NotFound');
        self::assertNull($result);
    }


}
