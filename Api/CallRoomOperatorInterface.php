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
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Interface CallRoomOperatorInterface
 * @package BelSmol\VideoCall\Api
 */
interface CallRoomOperatorInterface
{
    /**
     * @return CallRoomInterface
     */
    public function initNewRoom(): CallRoomInterface;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return CallRoomInterface|null
     */
    public function getRoomByParams(SearchCriteriaBuilder $searchCriteriaBuilder): ?CallRoomInterface;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return array
     */
    public function getRoomsByParam(SearchCriteriaBuilder $searchCriteriaBuilder): array;

    /**
     * @return SearchCriteriaBuilder
     */
    public function initSearchCriteriaBuilder(): SearchCriteriaBuilder;

    /**
     * @param CallRoomInterface $room
     */
    public function delete(CallRoomInterface $room): void;

    /**
     * @param CallRoomInterface $room
     */
    public function save(CallRoomInterface $room): void;
}
