<?php

namespace Supriadi\BelajarPhpMvc\Service;

use Supriadi\BelajarPhpMvc\Config\Database;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Exception\ValidationException;
use Supriadi\BelajarPhpMvc\Model\UserLoginRequest;
use Supriadi\BelajarPhpMvc\Model\UserLoginResponse;
use Supriadi\BelajarPhpMvc\Model\UserPasswordUpdateRequest;
use Supriadi\BelajarPhpMvc\Model\UserPasswordUpdateResponse;
use Supriadi\BelajarPhpMvc\Model\UserProfileUpdateRequest;
use Supriadi\BelajarPhpMvc\Model\UserProfileUpdateResponse;
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

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->getId());
        if ($user == null) {
            throw new ValidationException("id or password is wrong");
        }

        if (password_verify($request->getPassword(), $user->getPassword())) {
            $response = new UserLoginResponse();
            $response->setUser($user);
            return $response;
        } else {
            throw new ValidationException("id or password is wrong");
        }
    }

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if ($request->getId() == null || $request->getPassword() == null
            || trim($request->getId()) == "" || trim($request->getPassword()) == "") {
            throw new ValidationException("Id,Password can not blank");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfileUpdateRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->getId());

            if ($user == null) {
                throw new ValidationException('User is not found');
            }

            $user->setName($request->getName());
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->setUser($user);
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request)
    {
        if ($request->getId() == null || $request->getName() == null
            || trim($request->getId()) == "" || trim($request->getName()) == "") {
            throw new ValidationException("Id,Name can not blank");
        }
    }

    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->getId());

            if ($user == null) {
                throw new ValidationException('User is not found');
            }

            if (!password_verify($request->getOldPassword(), $user->getPassword())) {
                throw new ValidationException('Old password is wrong');
            }

            $user->setPassword(password_hash($request->getNewPassword(), PASSWORD_BCRYPT));

            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->setUser($user);

            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if ($request->getId() == null || $request->getOldPassword() == null || $request->getNewPassword() == null
            || trim($request->getId()) == "" || trim($request->getOldPassword()) == ""
            || trim($request->getNewPassword()) == "") {
            throw new ValidationException("Id, Old Password, New Password can not blank");
        }
    }
}