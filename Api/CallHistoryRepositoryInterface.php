<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Api\Data\CallHistorySearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CallHistoryRepositoryInterface
 * @package BelSmol\VideoCall\Api
 */
interface CallHistoryRepositoryInterface
{
    /**
     * Create or update a data
     *
     * @param CallHistoryInterface $history
     * @return CallHistoryInterface
     */
    public function save(CallHistoryInterface $history): CallHistoryInterface;

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
     * @param CallHistoryInterface $history
     * @return bool
     */
    public function delete(CallHistoryInterface $history): bool;

    /**
     * Get by id
     *
     * @param int $id
     */
    public function getById(int $id): CallHistoryInterface;

    /**
     * Get list by search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return CallHistorySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): CallHistorySearchResultsInterface;
}
