<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\Data\ManagerSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ManagerRepositoryInterface
 * @package BelSmol\VideoCall\Api
 */
interface ManagerRepositoryInterface
{
    /**
     * Create or update a data
     *
     * @param ManagerInterface $manager
     * @return ManagerInterface
     */
    public function save(ManagerInterface $manager): ManagerInterface;

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
     * @param ManagerInterface $manager
     * @return bool
     */
    public function delete(ManagerInterface $manager): bool;

    /**
     * Get by id
     *
     * @param int $id
     * @return ManagerInterface
     */
    public function getById(int $id): ManagerInterface;

    /**
     * Get list by search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return ManagerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ManagerSearchResultsInterface;
}
