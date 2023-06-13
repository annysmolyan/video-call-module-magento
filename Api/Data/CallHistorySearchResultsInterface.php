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
 * Interface CallHistorySearchResultsInterface
 *
 * Interface for BelSmol\VideoCall\Api\Data\CallHistoryInterface search criteria result
 *
 * WARNING: Dont use here strict type because of Magento\Framework\Api\SearchResults inheriting
 * @package BelSmol\VideoCall\Api\Data
 */
interface CallHistorySearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return CallHistoryInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param CallHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
