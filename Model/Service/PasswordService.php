<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Service;

use BelSmol\VideoCall\Api\PasswordServiceInterface;
use Exception;
use Magento\Framework\Encryption\EncryptorInterface;

/**
 * Class PasswordService
 * Generate random password
 * @package BelSmol\VideoCall\Model\Service
 */
class PasswordService implements PasswordServiceInterface
{
    protected const GENERATED_PASSWORD_CHARS = "0123456789abcdefghijklmnopqrstuvwxyz";

    protected EncryptorInterface $encryptor;

    /**
     * @param EncryptorInterface $encryptor
     */
    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * Compare if entered password is valid or not
     * @param string $enteredPassword
     * @param string $userPasswordHash
     * @return bool
     * @throws Exception
     */
    public function isPasswordValid(string $enteredPassword, string $userPasswordHash): bool
    {
        return $this->encryptor->validateHash($enteredPassword, $userPasswordHash);
    }

    /**
     * @param int $minLength
     * @param int $maxLength
     * @return string
     * @throws Exception
     */
    public function generateRandomPassword(
        int $minLength = self::DEFAULT_MIN_PASS_GENERATION_LENGTH,
        int $maxLength = self::DEFAULT_MAX_PASS_GENERATION_LENGTH
    ): string {
        $passLength = random_int($minLength, $maxLength);
        return substr(str_shuffle(self::GENERATED_PASSWORD_CHARS), 0, $passLength);
    }

    /**
     * @param string $password
     * @param bool $useSalt
     * @return string
     */
    public function createPasswordHash(string $password, bool $useSalt = false): string
    {
        return $this->encryptor->getHash($password, $useSalt);
    }

    /**
     * @param string $password
     * @param string $passwordConfirmation
     * @return bool
     */
    public function isPasswordConfirmed(string $password, string $passwordConfirmation): bool
    {
        return $password === $passwordConfirmation;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordLengthValid(string $password): bool
    {
        $passwordSize = strlen($password);
        return $passwordSize >= self::MIN_PASS_LENGTH && $passwordSize <= self::MAX_PASS_LENGTH;
    }
}
