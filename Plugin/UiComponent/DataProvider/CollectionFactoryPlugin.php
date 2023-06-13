<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Plugin\UiComponent\DataProvider;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory as Subject;

/**
 * Class CollectionFactoryPlugin
 * @package BelSmol\VideoCall\Plugin\UiComponent\DataProvider
 */
class CollectionFactoryPlugin
{
    private const CALL_HISTORY_REQUEST_NAME = "history_list_data_source";

    /**
     * Plugin for admin grid collection.
     * @param Subject $subject
     * @param $result
     * @param $requestName
     * @return Collection
     */
    public function afterGetReport(Subject $subject, $result, $requestName): Collection {

        if ($requestName == self::CALL_HISTORY_REQUEST_NAME) {
            $this->joinManagerAndCustomerToCallHistory($result);
        }

        return $result;
    }

    /**
     * Join manager and customer columns for call history
     * @param $result
     * @return void
     */
    private function joinManagerAndCustomerToCallHistory($result): void
    {
        $result->getSelect()->joinLeft(
            ["manager_table" => $result->getTable(ManagerInterface::TABLE_NAME)],
            'main_table.manager_id = manager_table.entity_id',
            ["firstname as manager_firstname", "lastname as manager_lastname"]
        )->joinLeft(
            ["customer_table" => $result->getTable("customer_entity")],
            'main_table.customer_id = customer_table.entity_id',
            ["email as customer_email", "firstname as customer_firstname", "lastname as customer_lastname"]
        );
    }
}
