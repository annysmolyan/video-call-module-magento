<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;

/**
 * Class Delete
 * @package BelSmol\VideoCall\Controller\Adminhtml\Manager
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = "BelSmol_VideoCall::managerDelete";

    protected const POST_PARAM_ID = "id";
    protected const SUCCESS_MESSAGE = 'Manager has been deleted!';

    private ManagerRepositoryInterface $managerRepository;

    /**
     * @param Context $context
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        Context $context,
        ManagerRepositoryInterface $managerRepository
    ) {
        parent::__construct($context);
        $this->managerRepository = $managerRepository;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $managerId = (int)$this->getRequest()->getParam(self::POST_PARAM_ID);

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $manager = $this->managerRepository->getById($managerId);
            $this->managerRepository->delete($manager);
            $this->messageManager->addSuccessMessage(__(self::SUCCESS_MESSAGE));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return $resultRedirect->setPath('*/*/edit', ["id" => $managerId, '_current' => true]);
        }

        return $resultRedirect->setPath('*/*/index', ['_current' => true]);
    }
}
