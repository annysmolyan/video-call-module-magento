<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ResourceModel;

use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CallRoom
 * @package BelSmol\VideoCall\Model\ResourceModel
 */
class CallRoom extends AbstractDb
{
    /**
     * Resource initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CallRoomInterface::TABLE_NAME, CallRoomInterface::ENTITY_ID);
    }
}
