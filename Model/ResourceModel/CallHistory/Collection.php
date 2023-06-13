<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ResourceModel\CallHistory;

use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Model\CallHistory as Model;
use BelSmol\VideoCall\Model\ResourceModel\CallHistory as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = CallHistoryInterface::ENTITY_ID;

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
