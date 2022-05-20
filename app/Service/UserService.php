<?php

namespace Supriadi\BelajarPhpMvc\Service;

use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Exception\ValidationException;
use Supriadi\BelajarPhpMvc\Model\UserRegisterRequest;
use Supriadi\BelajarPhpMvc\Model\UserRegisterResponse;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->getId());
            if ($user != null) {
                throw new ValidationException("User Id already exists");
            }

            $user = new User();
            $user->setId($request->getId());
            $user->setName($request->getName());
            $user->setPassword(password_hash($request->getPassword(), PASSWORD_BCRYPT));

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->setUser($user);

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if ($request->getId() == null || $request->getName() == null || $request->getPassword() == null
            || trim($request->getId()) == "" || trim($request->getName()) == ""
            || trim($request->getPassword()) == "") {
            throw new ValidationException("Id,Name,Password can not blank");
        }
    }
}