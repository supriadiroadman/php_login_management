<?php

namespace Supriadi\BelajarPhpMvc\Service;

use Supriadi\BelajarPhpMvc\Domain\Session;
use Supriadi\BelajarPhpMvc\Domain\User;
use Supriadi\BelajarPhpMvc\Repository\SessionRepository;
use Supriadi\BelajarPhpMvc\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = "X-SUPRIADI-SESSION";

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }


    public function create(string $userId): Session
    {
        $session = new Session();
        $session->setId(uniqid());
        $session->setUserId($userId);
        $this->sessionRepository->save($session);

        setcookie(self::$COOKIE_NAME, $session->getId(), time() + (60 * 60 * 24 * 30), "/");
        return $session;
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);

        // Hapus Cookie dari browser
        setcookie(self::$COOKIE_NAME, '', 1, "/");
    }

    public function current(): User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->findById($sessionId);

        if ($session == null) {
            return null;
        }

        $user = $this->userRepository->findById($session->getUserId());
        return $user;
    }
}