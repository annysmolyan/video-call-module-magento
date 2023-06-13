<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Interface CustomerOperatorInterface
 * @package BelSmol\VideoCall\Api
 */
interface CustomerOperatorInterface
{
    /**
     * @param int $customerId
     * @return bool
     */
    public function isCustomerSessionValid(int $customerId): bool;

    /**
     * @return int|null
     */
    public function getLoggedInCustomerId(): ?int;

    /**
     * @param int $id
     * @return CustomerInterface|null
     */
    public function getCustomerById(int $id): ?CustomerInterface;
}
