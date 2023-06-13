<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Service;

use BelSmol\VideoCall\Api\TokenServiceInterface;
use Exception;

/**
 * Class TokenService
 * Create token for calls.
 * @package BelSmol\VideoCall\Model\Service
 */
class TokenService implements TokenServiceInterface
{
    /**
     * Return base64 decoded token. Token represent the following:
     * "token"_[timestamp]
     *
     * @return string
     * @throws Exception
     */
    public function generateToken(): string
    {
        $token = 'token' . '_' . time();
        return base64_encode($token);
    }
}
