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
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Login
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class Login implements HttpGetActionInterface, ManagerAccountControllerInterface
{
    protected const PAGE_TITLE = "Manager Account Login";

    private PageFactory $resultPageFactory;
    private AuthHandlerInterface $authHandler;
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AuthHandlerInterface $authHandler
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->authHandler = $authHandler;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Login manager controller. Display login form
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if ($this->authHandler->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath("videocall/manager/dashboard");
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__(self::PAGE_TITLE));

        return $resultPage;
    }
}
