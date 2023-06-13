<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ResourceModel;

use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CallHistory
 * @package BelSmol\VideoCall\Model\ResourceModel
 */
class CallHistory extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(CallHistoryInterface::TABLE_NAME, CallHistoryInterface::ENTITY_ID);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    public function truncateTable(): CallHistory
    {
        if ($this->getConnection()->getTransactionLevel() > 0) {
            $this->getConnection()->delete($this->getMainTable());
        } else {
            $this->getConnection()->truncateTable($this->getMainTable());
        }
        return $this;
    }
}
