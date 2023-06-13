<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api\Data;

/**
 * Interface ManagerSearchResultsInterface
 * Interface for BelSmol\VideoCall\Api\Data\ManagerInterface search criteria result
 *
 * WARNING: Dont use here strict type because of Magento\Framework\Api\SearchResults inheriting
 *
 * @package BelSmol\VideoCall\Api\Data
 */
interface ManagerSearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return ManagerInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param ManagerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
