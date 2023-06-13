<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ManagerAuth;

use BelSmol\VideoCall\Api\AuthenticatorInterface;
use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\AuthSessionInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Vault\Exceptions\AuthenticationException;

/**
 * Class Handler
 * Manage User login/logout and user data in session
 * @package BelSmol\VideoCall\Model\ManagerAuth
 */
class Handler implements AuthHandlerInterface
{
    const EVENT_LOGIN_SUCCESS = "belsmol_manager_login_success";
    const EVENT_LOGIN_FAILED = "belsmol_manager_login_failed";

    private AuthenticatorInterface $authenticator;
    private EventManager $eventManager;
    private AuthSessionInterface $authSession;
    private ManagerRepositoryInterface $managerRepository;

    /**
     * @param AuthenticatorInterface $authenticator
     * @param EventManager $eventManager
     * @param AuthSessionInterface $authSession
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        AuthenticatorInterface $authenticator,
        EventManager $eventManager,
        AuthSessionInterface $authSession,
        ManagerRepositoryInterface $managerRepository
    ) {
        $this->authenticator = $authenticator;
        $this->eventManager = $eventManager;
        $this->authSession = $authSession;
        $this->managerRepository = $managerRepository;
    }

    /**
     * Login user and put into session
     *
     * @param string $login
     * @param string $password
     */
    public function login(string $login, string $password): void
    {
        try {
            $manager = $this->authenticator->authenticate($login, $password);
            $this->managerRepository->save($manager);
            $this->authSession->setManager($manager);
            $this->authSession->processLogin();
            $this->dispatchEvent(self::EVENT_LOGIN_SUCCESS, ['manager' => $manager]);
        } catch (AuthenticationException $exception) {
            $this->dispatchEvent(self::EVENT_LOGIN_FAILED, ["login" => $login, "password" => $password]);
            throw $exception;
        }
    }

    /**
     * Logout Manager user
     * @return void
     */
    public function logout(): void
    {
        $this->authSession->processLogout();
    }

    /**
     * Check if current user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->authSession->isLoggedIn();
    }

    /**
     * Return current (successfully authenticated) manager,
     * @return null|ManagerInterface
     */
    public function getUser(): ?ManagerInterface
    {
        return $this->authSession->getManager();
    }

    /**
     * @param string $eventName
     * @param array $data
     */
    protected function dispatchEvent(string $eventName, array $data): void
    {
        $this->eventManager->dispatch($eventName, $data);
    }
}
