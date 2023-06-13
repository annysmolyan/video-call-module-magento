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
 * Interface PasswordServiceInterface
 * @package BelSmol\VideoCall\Api
 */
interface PasswordServiceInterface
{
    const MIN_PASS_LENGTH = 6;
    const MAX_PASS_LENGTH = 20;
    const DEFAULT_MIN_PASS_GENERATION_LENGTH = 6;
    const DEFAULT_MAX_PASS_GENERATION_LENGTH = 10;

    /**
     * Compare if entered password is valid or not
     * @param string $enteredPassword
     * @param string $userPasswordHash
     * @return bool
     */
    public function isPasswordValid(string $enteredPassword, string $userPasswordHash): bool;

    /**
     * @param int $minLength
     * @param int $maxLength
     * @return string
     */
    public function generateRandomPassword(
        int $minLength = self::MIN_PASS_LENGTH,
        int $maxLength = self::MAX_PASS_LENGTH
    ): string;

    /**
     * @param string $password
     * @param bool $useSalt
     * @return string
     */
    public function createPasswordHash(string $password, bool $useSalt = false): string;

    /**
     * @param string $password
     * @param string $passwordConfirmation
     * @return bool
     */
    public function isPasswordConfirmed(string $password, string $passwordConfirmation): bool;

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordLengthValid(string $password): bool;
}
