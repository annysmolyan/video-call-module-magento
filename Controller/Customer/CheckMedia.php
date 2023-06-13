<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Customer;

use BelSmol\VideoCall\Api\CustomerSupportControllerInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class CheckMedia
 * WARNING! The class has plugin CustomerSupportControllerPlugin.
 * The plugin checks if a user should be redirected to another page
 * in case if the module disabled or a customer was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Customer
 */
class CheckMedia implements HttpGetActionInterface, CustomerSupportControllerInterface
{
    protected const PAGE_TITLE = "Support Video Call - checking media devices";

    private PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Check user media before starting video call
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $page = $this->resultPageFactory->create();
        $page->getConfig()->getTitle()->set(__(self::PAGE_TITLE));
        return $page;
    }
}
