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
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Api\PasswordServiceInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Vault\Exceptions\AuthenticationException;

/**
 * Class Authenticator
 * @package BelSmol\VideoCall\Model\ManagerAuth
 */
class Authenticator implements AuthenticatorInterface
{
    protected const MSG_INVALID_CREDENTIALS = "Invalid login or password.";
    protected const MSG_AUTH_ERROR = "The account sign-in was incorrect or your account is disabled temporarily. Please wait and try again later.";

    private ManagerRepositoryInterface $managerRepository;
    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;
    private PasswordServiceInterface $passwordService;

    /**
     * @param ManagerRepositoryInterface $managerRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param PasswordServiceInterface $passwordService
     */
    public function __construct(
        ManagerRepositoryInterface $managerRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        PasswordServiceInterface $passwordService
    ) {
        $this->managerRepository = $managerRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->passwordService = $passwordService;
    }

    /**
     * @param string $login
     * @param string $password
     * @return ManagerInterface
     * @throws Exception
     */
    public function authenticate(string $login, string $password): ManagerInterface
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteriaBuilder->addFilter(ManagerInterface::LOGIN, $login);
        $searchCriteriaBuilder->addFilter(ManagerInterface::IS_ACTIVE, true);
        $searchCriteria = $searchCriteriaBuilder->create();
        $searchResult = $this->managerRepository->getList($searchCriteria)->getItems();

        if (!$searchResult) {
            $this->throwException(self::MSG_INVALID_CREDENTIALS);
        }

        $manager = reset($searchResult);

        if (!$this->verifyIdentity($manager, $password)) {
            $this->throwException(self::MSG_AUTH_ERROR);
        }

        return $manager;
    }

    /**
     * Ensure that provided password matches the current user password.
     *
     * @param ManagerInterface $manager
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function verifyIdentity(ManagerInterface $manager, string $password): bool
    {
        return $this->passwordService->isPasswordValid($password, $manager->getPasswordHash())
            && $manager->getIsActive();
    }

    /**
     * Throws specific Authentication exception
     *
     * @param string|null $msg
     * @return void
     * @static
     */
    protected function throwException(string $msg = null): void
    {
        throw new AuthenticationException(__($msg));
    }
}
