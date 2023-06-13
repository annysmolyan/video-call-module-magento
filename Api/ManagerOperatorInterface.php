<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use Exception;

/**
 * Interface ManagerOperatorInterface
 * @package BelSmol\VideoCall\Api
 */
interface ManagerOperatorInterface
{
    /**
     * @param int $managerId
     * @return bool
     */
    public function isManagerIdExists(int $managerId): bool;

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
    ): void;

    /**
     * @param string $email
     * @param string $password
     * @throws Exception
     */
    public function setManagerPasswordByEmail(string $email, string $password): void;

    /**
     * @param string $token
     * @return ManagerInterface|null
     */
    public function getLoggedInManagerByToken(string $token): ?ManagerInterface;
}
