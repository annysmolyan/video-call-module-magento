<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Operator;

use BelSmol\VideoCall\Api\AuthHandlerInterface as ManagerAuthHandler;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerOperatorInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Api\PasswordServiceInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;

/**
 * Class ManagerOperator
 * @package BelSmol\VideoCall\Model\Operator
 */
class ManagerOperator implements ManagerOperatorInterface
{
    protected const MSG_INCORRECT_USER_PASSWORD = "Wrong user password.";
    protected const MSG_WRONG_PASSWORD_CONFIRMATION = "The passwords should match.";
    protected const MSG_WRONG_PASSWORD_LENGTH = "Password length must be between 6 and 20 chars";

    protected ManagerRepositoryInterface $managerRepository;
    protected PasswordServiceInterface $passwordService;
    protected ManagerAuthHandler $managerAuthHandler;
    protected SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    /**
     * @param ManagerRepositoryInterface $managerRepository
     * @param PasswordServiceInterface $passwordService
     * @param ManagerAuthHandler $managerAuthHandler
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        ManagerRepositoryInterface $managerRepository,
        PasswordServiceInterface $passwordService,
        ManagerAuthHandler $managerAuthHandler,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->managerRepository = $managerRepository;
        $this->passwordService = $passwordService;
        $this->managerAuthHandler = $managerAuthHandler;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * @param int $managerId
     * @return bool
     */
    public function isManagerIdExists(int $managerId): bool
    {
        try {
            $this->managerRepository->getById($managerId);
            $isValid = true;
        } catch (Exception $exception) {
            $isValid = false;
        }
        return $isValid;
    }

    /**
     * @param ManagerInterface $manager
     * @param string $currentPassword
     * @param string $newPassword
     * @param string $passwordConfirmation
     * @throws Exception
     */
    public function updateManagerPassword(
        ManagerInterface $manager,
        string $currentPassword,
        string $newPassword,
        string $passwordConfirmation
    ): void {

        if (!$this->passwordService->isPasswordValid($currentPassword, $manager->getPasswordHash())) {
            throw new Exception(self::MSG_INCORRECT_USER_PASSWORD);
        }

        if (!$this->passwordService->isPasswordConfirmed($newPassword, $passwordConfirmation)) {
            throw new Exception(self::MSG_WRONG_PASSWORD_CONFIRMATION);
        }

        if (!$this->passwordService->isPasswordLengthValid($newPassword)) {
            throw new Exception(__(
                self::MSG_WRONG_PASSWORD_LENGTH,
                PasswordServiceInterface::MIN_PASS_LENGTH,
                PasswordServiceInterface::MAX_PASS_LENGTH
            ));
        }

        $this->saveManagerPassword($manager, $newPassword);
    }

    /**
     * @param string $email
     * @param string $password
     * @throws Exception
     */
    public function setManagerPasswordByEmail(string $email, string $password): void
    {
        if (!$this->passwordService->isPasswordLengthValid($password)) {
            throw new Exception(__(
                self::MSG_WRONG_PASSWORD_LENGTH,
                PasswordServiceInterface::MIN_PASS_LENGTH,
                PasswordServiceInterface::MAX_PASS_LENGTH
            ));
        }

        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteriaBuilder->addFilter(ManagerInterface::EMAIL, $email);
        $searchCriteria = $searchCriteriaBuilder->create();

        $searchResult = $this->managerRepository->getList($searchCriteria)->getItems();

        if (!$searchResult) {
            throw new Exception("Manager with email '" . $email . "' does not exist");
        }

        $manager = reset($searchResult);

        $this->saveManagerPassword($manager, $password);
    }

    /**
     * @param string $token
     * @return ManagerInterface|null
     */
    public function getLoggedInManagerByToken(string $token): ?ManagerInterface
    {
        $manager = $this->managerAuthHandler->getUser();
        $isTokenSessionActive = $manager && ($manager->getToken() == $token);
        return $isTokenSessionActive ? $manager : null;
    }

    /**
     * @param ManagerInterface $manager
     * @param string $password
     */
    protected function saveManagerPassword(ManagerInterface $manager, string $password): void
    {
        $passwordHash = $this->passwordService->createPasswordHash($password);
        $manager->setPasswordHash($passwordHash);
        $this->managerRepository->save($manager);
    }
}
