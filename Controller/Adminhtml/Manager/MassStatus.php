<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\Manager;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class MassStatus
 * @package BelSmol\VideoCall\Controller\Adminhtml\Manager
 */
class MassStatus extends AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = "BelSmol_VideoCall::managerEdit";

    /**
     * Execute action based on request and return result
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        $collection = $this->loadCollection();
        $statusValue = (int)$this->getRequest()->getParam('status');
        $recordDeleted = 0;

        foreach ($collection->getItems() as $item) {
            $item->setIsActive((bool)$statusValue);
            $this->managerRepository->save($item);
            $recordDeleted++;
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $recordDeleted));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
