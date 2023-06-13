<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Manager;

use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\ManagerAccountControllerInterface;
use BelSmol\VideoCall\Api\MessageInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Logout
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class Logout implements HttpGetActionInterface, ManagerAccountControllerInterface
{
    private AuthHandlerInterface $authHandler;
    private ManagerInterface $messageManager;
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param Context $context
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(
        Context $context,
        AuthHandlerInterface $authHandler
    ) {
        $this->authHandler = $authHandler;
        $this->messageManager = $context->getMessageManager();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Logout manager
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $this->authHandler->logout();
        $this->messageManager->addSuccessMessage(__(MessageInterface::MSG_MANAGER_LOGOUT));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath("videocall/manager/login");
    }
}
