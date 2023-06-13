<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

/**
 * Interface AuthSessionInterface
 * @package BelSmol\VideoCall\Api
 */
interface AuthSessionInterface
{
    /**
     * Perform login specific actions
     *
     * @return void
     */
    public function processLogin(): void;

    /**
     * Perform logout specific actions
     *
     * @return void
     */
    public function processLogout(): void;

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * Prolong storage lifetime
     *
     * @return void
     */
    public function prolong(): void;
}
