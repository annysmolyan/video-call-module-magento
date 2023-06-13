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
 * Interface AuthHandler
 * @package BelSmol\VideoCall\Api
 */
interface AuthHandlerInterface
{
    /**
     * Login user and put into session
     *
     * @param string $login
     * @param string $password
     */
    public function login(string $login, string $password): void;

    /**
     * Logout Manager user
     * @return void
     */
    public function logout(): void;

    /**
     * Check if current user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * Return current (successfully authenticated) manager,
     * @return null|ManagerInterface
     */
    public function getUser(): ?ManagerInterface;
}
