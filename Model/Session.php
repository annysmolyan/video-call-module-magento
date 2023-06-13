<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model;

use Magento\Framework\Session\SessionManager;

/**
 * Class ManagerSession
 * Manager user session object. Handle user data, locales and so on
 * @package BelSmol\VideoCall\Model
 */
class Session extends SessionManager
{
    /**
     * @OVERRIDE
     * Skip path validation in manager area
     *
     * @param string $path
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isValidForPath($path): bool
    {
        return true;
    }
}
