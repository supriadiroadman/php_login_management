<?php

namespace Supriadi\BelajarPhpMvc\Controller;

use Supriadi\BelajarPhpMvc\App\View;
use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Exception\ValidationException;
use Supriadi\BelajarPhpMvc\Model\UserLoginRequest;
use Supriadi\BelajarPhpMvc\Model\UserRegisterRequest;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;
use Supriadi\BelajarPhpMvc\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
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
            $this->userService->login($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/login', [
                    'title' => 'Login user',
                    'error' => $exception->getMessage()]
            );
        }
    }
}