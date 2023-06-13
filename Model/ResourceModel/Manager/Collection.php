<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ResourceModel\Manager;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Model\Manager;
use BelSmol\VideoCall\Model\ResourceModel\Manager as ManagerResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package BelSmol\VideoCall\Model\Manager
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = ManagerInterface::ENTITY_ID;

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(Manager::class, ManagerResourceModel::class);
    }

    /**
     * Perform operations after collection load
     *
     * @return Collection
     */
    protected function _afterLoad(): Collection
    {
        $this->loadAssignedWebsites();
        return parent::_afterLoad();
    }

    /**
     * Load assigned stores to manager
     * @return void
     */
    protected function loadAssignedWebsites(): void
    {
        $managerIds = $this->getColumnValues($this->_idFieldName);

        if (!count($managerIds)) {
            return;
        }

        $connection = $this->getConnection();

        $select = $this->getConnection()
            ->select()
            ->from([
                ManagerInterface::WEBSITE_RELATION_TABLE => $this->getTable(ManagerInterface::WEBSITE_RELATION_TABLE),
            ])
            ->where(ManagerInterface::WEBSITE_RELATION_TABLE . '.' . ManagerInterface::WEBSITE_MANAGER_ID . " IN (?)", $managerIds);

        $assignedWebsites = $connection->fetchAll($select);

        if (!$assignedWebsites) {
            return;
        }

        $websitesData = [];

        foreach ($assignedWebsites as $websiteData) {
            $managerId = $websiteData[ManagerInterface::WEBSITE_MANAGER_ID];
            $websitesData[$managerId][] = $websiteData[ManagerInterface::WEBSITE_WEBSITE_ID];
        }

        foreach ($this as $item) {
            if (isset($websitesData[$item->getId()])) {
                $item->setData(ManagerInterface::WEBSITES, $websitesData[$item->getId()]);
            }
        }
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    public function joinWebsiteRelationTable(): void
    {
        $this->getSelect()->join(
            ["website_table" => $this->getTable(ManagerInterface::WEBSITE_RELATION_TABLE)],
            "main_table.entity_id = website_table." . ManagerInterface::WEBSITE_MANAGER_ID,
            []
        )->distinct(true);

        parent::_renderFiltersBefore();
    }
}
