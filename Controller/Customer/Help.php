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
 * Class Help
 * WARNING! The class has plugin CustomerSupportControllerPlugin.
 * The plugin checks if a user should be redirected to another page
 * in case if the module disabled or a customer was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Customer
 */
class Help implements HttpGetActionInterface, CustomerSupportControllerInterface
{
    protected const PAGE_TITLE = "Customer Support";

    private PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__(self::PAGE_TITLE));
        return $resultPage;
    }
}
