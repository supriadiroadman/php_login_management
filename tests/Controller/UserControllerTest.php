<?php

namespace Supriadi\BelajarPhpMvc\App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace Supriadi\BelajarPhpMvc\Service {

    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}

namespace Supriadi\BelajarPhpMvc\Controller {

    use PHPUnit\Framework\TestCase;
    use Supriadi\BelajarPhpMvc\Config\Database;
    use Supriadi\BelajarPhpMvc\Domain\Session;
    use Supriadi\BelajarPhpMvc\Domain\User;
    use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
    use Supriadi\BelajarPhpMvc\Repository\UserRepository;
    use Supriadi\BelajarPhpMvc\Service\SessionService;

    class UserControllerTest extends TestCase
    {
        private UserController $userController;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void
        {
            $this->userController = new UserController();

            $this->sessionRepository = new SessionRepository(Database::getConnection());
            $this->sessionRepository->deleteAll();

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
            $this->expectOutputRegex("[X-SUPRIADI-SESSION: ]");
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

        public function testLogout()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $session = new Session();
            $session->setId(uniqid());
            $session->setUserId($user->getId());
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

            $this->userController->logout();

            $this->expectOutputRegex("[Location: /]");
            $this->expectOutputRegex("[X-SUPRIADI-SESSION: ]");


        }

        public function testUpdateProfile()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $session = new Session();
            $session->setId(uniqid());
            $session->setUserId($user->getId());
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();
            $this->userController->updateProfile();

            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[adi]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Adi]");
        }

        public function testPostUpdateProfileSuccess()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $session = new Session();
            $session->setId(uniqid());
            $session->setUserId($user->getId());
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

            $_POST['name'] = 'Ganti nama';
            $this->userController->postUpdateProfile();
            $this->expectOutputRegex("[Location: /]");

            $result = $this->userRepository->findById('adi');
            self::assertEquals('Ganti nama', $result->getName());

        }

        public function testPostUpdateProfileValidationError()
        {
            $user = new User();
            $user->setId('adi');
            $user->setName('Adi');
            $user->setPassword(password_hash('rahasia', PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            $session = new Session();
            $session->setId(uniqid());
            $session->setUserId($user->getId());
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

            $_POST['name'] = '';
            $this->userController->postUpdateProfile();

            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[adi]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Id,Name can not blank]");
        }

    }
}


