<?php

namespace Supriadi\BelajarPhpMvc\Controller;

use Supriadi\BelajarPhpMvc\App\View;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Exception\ValidationException;
use Supriadi\BelajarPhpMvc\Model\UserLoginRequest;
use Supriadi\BelajarPhpMvc\Model\UserPasswordUpdateRequest;
use Supriadi\BelajarPhpMvc\Model\UserProfileUpdateRequest;
use Supriadi\BelajarPhpMvc\Model\UserRegisterRequest;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;
use Supriadi\BelajarPhpMvc\Service\SessionService;
use Supriadi\BelajarPhpMvc\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    public function register()
    {
        View::render('User/register', [
            'title' => 'Register New User',
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->setId($_POST['id']);
        $request->setName($_POST['name']);
        $request->setPassword($_POST['password']);

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch (ValidationException $exception) {
            View::render('User/register', [
                    'title' => 'Register New User',
                    'error' => $exception->getMessage()]
            );
        }
    }

    public function login()
    {
        View::render('User/login', [
            'title' => 'Login user'
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->setId($_POST['id']);
        $request->setPassword($_POST['password']);

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->getUser()->getId());
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/login', [
                    'title' => 'Login user',
                    'error' => $exception->getMessage()]
            );
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/');
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();

        View::render('User/profile', [
            'title' => 'Update user profile',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName()
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->setId($user->getId());
        $request->setName($_POST['name']);

        try {
            $this->userService->updateProfile($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/profile', [
                'title' => 'Update user profile',
                'error' => $exception->getMessage(),
                'user' => [
                    'id' => $user->getId(),
                    'name' => $_POST['name']
                ]
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();

        View::render('User/password', [
            'title' => 'Update user password',
            'user' => [
                'id' => $user->getId(),
            ]
        ]);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->setId($user->getId());
        $request->setOldPassword($_POST['oldPassword']);
        $request->setNewPassword($_POST['newPassword']);

        try {
            $this->userService->updatePassword($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/password', [
                'title' => 'Update user password',
                'error' => $exception->getMessage(),
                'user' => [
                    'id' => $user->getId(),
                ]
            ]);
        }
    }

}