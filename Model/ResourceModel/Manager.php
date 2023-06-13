<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ResourceModel;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Manager
 * @package BelSmol\VideoCall\Model\ResourceModel
 */
class Manager extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ManagerInterface::TABLE_NAME, ManagerInterface::ENTITY_ID);
    }

    /**
     * @override
     * Perform actions after object load
     *
     * @param AbstractModel|DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()){
            $this->joinWebsites($object);
        }

        return $this;
    }

    /**
     * Add websites to manager when load manager by id
     * @param AbstractModel $object
     */
    private function joinWebsites(AbstractModel $object): void
    {
        $connection = $this->getConnection();

        $select = $this->getConnection()
            ->select()
            ->from([
                ManagerInterface::WEBSITE_RELATION_TABLE => $this->getTable(ManagerInterface::WEBSITE_RELATION_TABLE),
            ])
            ->where(ManagerInterface::WEBSITE_RELATION_TABLE . '.' . ManagerInterface::WEBSITE_MANAGER_ID . " = " . $object->getId());

        $assignedWebsites = $connection->fetchAll($select);

        if (!$assignedWebsites) {
            return;
        }

        $websites = [];

        foreach ($assignedWebsites as $websiteData) {
            $websites[] = $websiteData[ManagerInterface::WEBSITE_WEBSITE_ID];
        }

        $object->setData(ManagerInterface::WEBSITES, $websites);
    }

    /**
     * @override
     * Actions before manager save
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _beforeSave(AbstractModel $object): AbstractDb
    {
        $managerId = (int)$object->getId();

        if ($managerId) {
            $this->deleteAssignedWebsites($managerId);
        }

        return $this;
    }

    /**
     * @override
     * Actions after manager save
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterSave(AbstractModel $object): AbstractDb
    {
        $websites = $object->getData(ManagerInterface::WEBSITES);

        if ($websites) {
            $this->saveAssignedWebsites((int)$object->getId(), $websites);
        }

        return $this;
    }

    /**
     * Save assigned websites for manager
     * @param int $managerId
     * @param array $websites
     */
    private function saveAssignedWebsites(int $managerId, array $websites): void
    {
        $data = [];
        $table = $this->getTable(ManagerInterface::WEBSITE_RELATION_TABLE);

        foreach ($websites as $websiteId) {
            $data[] = [
                ManagerInterface::WEBSITE_MANAGER_ID => $managerId,
                ManagerInterface::WEBSITE_WEBSITE_ID => (int)$websiteId
            ];
        }

        $this->getConnection()->insertMultiple($table, $data);
    }

    /**
     * Delete assigned websites to manager
     * @param int $managerId
     * @return void
     */
    private function deleteAssignedWebsites(int $managerId): void
    {
        $table = $this->getTable(ManagerInterface::WEBSITE_RELATION_TABLE);

        $this->getConnection()->delete($table, [
            ManagerInterface::WEBSITE_MANAGER_ID . " = ?" => $managerId,
        ]);
    }
}
