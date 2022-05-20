<?php

namespace Supriadi\BelajarPhpMvc\Repository;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
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


}
