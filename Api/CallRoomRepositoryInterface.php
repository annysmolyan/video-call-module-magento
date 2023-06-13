<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Api\Data\CallRoomSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CallRoomRepositoryInterface
 * @package BelSmol\VideoCall\Api
 */
interface CallRoomRepositoryInterface
{
    /**
     * Create or update a data
     *
     * @param CallRoomInterface $room
     * @return CallRoomInterface
     */
    public function save(CallRoomInterface $room): CallRoomInterface;

    /**
     * Delete by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * Delete item.
     *
     * @param CallRoomInterface $room
     * @return bool
     */
    public function delete(CallRoomInterface $room): bool;

    /**
     * Get by id
     *
     * @param int $id
     * @return CallRoomInterface
     */
    public function getById(int $id): CallRoomInterface;

    /**
     * Get list by search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return CallRoomSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $criteria): CallRoomSearchResultInterface;
}
