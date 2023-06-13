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
 * Interface MessageInterface
 * Message global set
 * @package BelSmol\VideoCall\Api
 */
interface MessageInterface
{
    const MSG_INVALID_FORM_KEY = "Invalid Form Key. Please refresh the page.";
    const MSG_PASSWORD_UPDATED = "The password has been updated";
    const MSG_MANAGER_LOGOUT = "You have been logged out.";
    const MSG_DATA_UPDATED = "The data has been updated";
}
