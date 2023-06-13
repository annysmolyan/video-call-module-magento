<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\History;

use BelSmol\VideoCall\Model\ResourceModel\CallHistory as CallHistoryResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Truncate
 * @package BelSmol\VideoCall\Controller\Adminhtml\History
 */
class Truncate extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = "BelSmol_VideoCall::manager";
    private CallHistoryResource $callHistoryResource;

    /**
     * @param Context $context
     * @param CallHistoryResource $callHistoryResource
     */
    public function __construct(
        Context $context,
        CallHistoryResource $callHistoryResource
    ) {
        parent::__construct($context);
        $this->callHistoryResource = $callHistoryResource;
    }

    /**
     * Truncate call history table
     * DONT USE RETURN TYPE HERE
     */
    public function execute(): ResultInterface
    {
       $this->callHistoryResource->truncateTable();
       return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
