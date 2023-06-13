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

/**
 * Interface ManagerAccountManagementInterface
 * @package BelSmol\VideoCall\Api
 */
interface AuthenticatorInterface
{
    /**
     * @param string $login
     * @param string $password
     * @return ManagerInterface
     */
    public function authenticate(string $login, string $password): ManagerInterface;

    /**
     * Ensure that provided password matches the current user password.
     *
     * @param ManagerInterface $manager
     * @param string $password
     * @return bool
     */
    public function verifyIdentity(ManagerInterface $manager, string $password): bool;
}
