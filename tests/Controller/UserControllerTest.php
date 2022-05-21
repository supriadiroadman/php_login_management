<?php

namespace Supriadi\BelajarPhpMvc\App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace Supriadi\BelajarPhpMvc\Controller {

    use PHPUnit\Framework\TestCase;
    use Supriadi\BelajarPhpMvc\Config\Database;
    use Supriadi\BelajarPhpMvc\Domain\User;
    use Supriadi\BelajarPhpMvc\Repository\UserRepository;

    class UserControllerTest extends TestCase
    {
        private UserController $userController;
        private UserRepository $userRepository;

        protected function setUp(): void
        {
            $this->userController = new UserController();
            $this->userRepository = new UserRepository(Database::getConnection());
            $this->userRepository->deleteAll();

            putenv("mode=test");
        }

        public function testRegister()
        {
            $this->userController->register();

            $this->expectOutputRegex('[Register]');
            $this->expectOutputRegex('[Id]');
            $this->expectOutputRegex('[Name]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[Register New User]');
        }

        public function testPostRegisterSuccess()
        {
            $_POST['id'] = 'adi';
            $_POST['name'] = 'Adi';
            $_POST['password'] = 'rahasia';

            $this->userController->postRegister();

            $this->expectOutputRegex("[Location: /users/login]");
        }

        public function testPostRegisterValidationError()
        {
            $_POST['id'] = '';
            $_POST['name'] = '';
            $_POST['password'] = '';

            $this->userController->postRegister();

            $this->expectOutputRegex('[Register]');
            $this->expectOutputRegex('[Id]');
            $this->expectOutputRegex('[Name]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[Register New User]');
            $this->expectOutputRegex('[Id,Name,Password can not blank]');

        }

        public function testPostRegisterDuplicate()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword('rahasia');

            $this->userRepository->save($user);

            $_POST['id'] = 'adi';
            $_POST['name'] = 'Adi';
            $_POST['password'] = 'rahasia';

            $this->userController->postRegister();

            $this->expectOutputRegex('[Register]');
            $this->expectOutputRegex('[Id]');
            $this->expectOutputRegex('[Name]');
            $this->expectOutputRegex('[Password]');
            $this->expectOutputRegex('[Register New User]');
            $this->expectOutputRegex('[User Id already exists]');
        }

        public function testLogin()
        {
            $this->userController->login();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
        }

        public function testLoginSuccess()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $_POST['id'] = 'adi';
            $_POST['password'] = 'rahasia';
            $this->userController->postLogin();

            $this->expectOutputRegex("[Location: /]");
        }

        public function testLoginValidationError()
        {
            $_POST['id'] = '';
            $_POST['password'] = '';
            $this->userController->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id,Password can not blank]");
        }

        public function testLoginUserNotFound()
        {
            $_POST['id'] = 'Notfound';
            $_POST['password'] = 'Notfound';
            $this->userController->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[id or password is wrong]");
        }

        public function testLoginWrongPassword()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $_POST['id'] = 'adi';
            $_POST['password'] = 'salah';
            $this->userController->postLogin();

            $this->expectOutputRegex("[Login user]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[id or password is wrong]");
        }
    }
}


