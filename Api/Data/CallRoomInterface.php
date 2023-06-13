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
 * Interface CallRoomInterface
 * @package BelSmol\VideoCall\Api\Data
 */
interface CallRoomInterface
{
    const TABLE_NAME = "belsmol_videocall_call_room";

    const ENTITY_ID = "entity_id";
    const MANAGER_ID = "manager_id";
    const CUSTOMER_ID = "customer_id";
    const ROOM_ID = "room_id";
    const WEBSITE_ID = "website_id";

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
     * @return ManagerInterface|null
     */
    public function getManager(): ?ManagerInterface;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @param int $id
     */
    public function setCustomerId(int $id): void;

    /**
     * @param string $id
     */
    public function setRoomId(string $id): void;

    /**
     * @return string
     */
    public function getRoomId(): string;

    /**
     * @param int $websiteId
     */
    public function setWebsiteId(int $websiteId): void;

    /**
     * @return int|null
     */
    public function getWebsiteId(): ?int;
}
