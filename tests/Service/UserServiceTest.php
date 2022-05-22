<?php

namespace Supriadi\BelajarPhpMvc\Service;

use PHPUnit\Framework\TestCase;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Exception\ValidationException;
use Supriadi\BelajarPhpMvc\Model\UserLoginRequest;
use Supriadi\BelajarPhpMvc\Model\UserProfileUpdateRequest;
use Supriadi\BelajarPhpMvc\Model\UserRegisterRequest;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {
        $request = new UserRegisterRequest();
        $request->setId('adi');
        $request->setName('Supriadi');
        $request->setPassword('rahasia');

        $response = $this->userService->register($request);
        self::assertEquals($request->getId(), $response->getUser()->getId());
        self::assertEquals($request->getName(), $response->getUser()->getName());
        self::assertNotEquals($request->getPassword(), $response->getUser()->getPassword());

        self::assertTrue(password_verify($request->getPassword(), $response->getUser()->getPassword()));
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->setId('');
        $request->setName('');
        $request->setPassword('');

        $response = $this->userService->register($request);
    }

    public function testRegisterDuplicate()
    {

        $user = new User();
        $user->setId('adi');
        $user->setName('Supriadi');
        $user->setPassword('rahasia');
        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->setId('adi');
        $request->setName('Supriadi');
        $request->setPassword('rahasia');
        $response = $this->userService->register($request);
    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->setId('adi');
        $request->setPassword('rahasia');

        $this->userService->login($request);
    }

    public function testLoginWrongPassword()
    {
        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->setId('adi');
        $request->setPassword('rahasia');

        $this->userService->login($request);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->setId('adi');
        $request->setPassword('rahasia');

        $response = $this->userService->login($request);

        self::assertEquals($request->getId(), $response->getUser()->getId());
    }

    public function testUpdateSuccess()
    {
        $user = new User();
        $user->setId('adi');
        $user->setName('Adi');
        $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
        $this->userRepository->save($user);

        $request = new UserProfileUpdateRequest();
        $request->setId('adi');
        $request->setName('Ganti nama');
        $this->userService->updateProfile($request);

        $result = $this->userRepository->findById($user->getId());

        self::assertEquals($request->getName(), $result->getName());


    }

    public function testUpdateValidationError()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->setId('');
        $request->setName('');
        $this->userService->updateProfile($request);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->setId('adi');
        $request->setName('Adi');
        $this->userService->updateProfile($request);
    }


}
