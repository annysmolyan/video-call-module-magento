<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api\Data;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Interface CallHistoryInterface
 * @package BelSmol\VideoCall\Api\Data
 */
interface CallHistoryInterface
{
    const TABLE_NAME = "belsmol_videocall_call_history";

    const ENTITY_ID = "entity_id";
    const MANAGER_ID = "manager_id";
    const CUSTOMER_ID = "customer_id";
    const DURATION = "duration";
    const DATE = "date";

    const CUSTOMER = "customer";
    const MANAGER = "manager";

    /**
     * @return int|null
     */
    public function getManagerId(): ?int;

    /**
     * @param int $id
     */
    public function setManagerId(int $id): void;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param int $id
     */
    public function setCustomerId(int $id): void;

    /**
     * In seconds
     * @return int
     */
    public function getDuration(): int;

    /**
     * In seconds
     * @param int $duration
     */
    public function setDuration(int $duration): void;

    /**
     * @return string
     */
    public function getDate(): string;

    /**
     * @param string $date
     */
    public function setDate(string $date = ""): void;

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @return ManagerInterface|null
     */
    public function getManager(): ?ManagerInterface;
}
