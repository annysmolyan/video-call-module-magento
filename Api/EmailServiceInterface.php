<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use BelSmol\VideoCall\Api\Data\EmailTemplateInterface;

/**
 * Interface EmailServiceInterface
 * @package BelSmol\VideoCall\Api
 */
interface EmailServiceInterface
{
    /**
     * @param EmailTemplateInterface $emailTemplate
     */
    public function sendEmail(EmailTemplateInterface $emailTemplate): void;
}
