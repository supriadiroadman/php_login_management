<?php

namespace Supriadi\BelajarPhpMvc\Repository;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();

        $user->setId('adi');
        $user->setName('Supriadi');
        $user->setPassword('rahasia');

        $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->getId());

        self::assertEquals($user->getId(), $result->getId());
        self::assertEquals($user->getName(), $result->getName());
        self::assertEquals($user->getPassword(), $result->getPassword());

    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById('not found');
        self::assertNull($user);

    }

    public function testUpdate()
    {
        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword('rahasia');
        $this->userRepository->save($user);

        $user->setName('Ganti nama');
        $this->userRepository->update($user);

        $result = $this->userRepository->findById($user->getId());

        self::assertEquals($user->getId(), $result->getId());
        self::assertEquals($user->getName(), $result->getName());
        self::assertEquals($user->getPassword(), $result->getPassword());
    }


}
