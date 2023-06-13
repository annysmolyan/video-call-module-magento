<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Manager;

use BelSmol\VideoCall\Api\ManagerAccountControllerInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Dashboard
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class Dashboard implements HttpGetActionInterface, ManagerAccountControllerInterface
{
    protected const PAGE_TITLE = "Dashboard | Manager Account";

    private PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Show manager dashboard page
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__(self::PAGE_TITLE));
        return $resultPage;
    }
}
