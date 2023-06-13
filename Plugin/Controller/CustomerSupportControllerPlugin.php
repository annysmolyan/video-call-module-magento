<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Plugin\Controller;

use BelSmol\VideoCall\Api\CustomerSupportControllerInterface as Subject;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Closure;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as MagentoHttpContext;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class CustomerSupportControllerPlugin
 * @package BelSmol\VideoCall\Plugin\Controller
 */
class CustomerSupportControllerPlugin
{
    private MagentoHttpContext $httpContext;
    private ConfigHelper $configHelper;
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param MagentoHttpContext $httpContext
     * @param ConfigHelper $configHelper
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        MagentoHttpContext $httpContext,
        ConfigHelper $configHelper,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->httpContext = $httpContext;
        $this->configHelper = $configHelper;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * Check if a user should be redirected to another page
     * in case if the module disabled or a customer was not logged in
     *
     * @param Subject $subject
     * @param Closure $proceed
     * @return ResultInterface
     */
    public function aroundExecute(Subject $subject, Closure $proceed): ResultInterface
    {
        $result = $proceed();

        if ($redirectPath = $this->getRedirectPath()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $result = $resultRedirect->setPath($redirectPath);
        }

        return $result;
    }

    /**
     * @return string|null
     */
    private function getRedirectPath(): ?string
    {
        $path = null;

        if (!$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH)) {
            $path = "customer/account/login";
        }

        if (!$this->configHelper->isModuleEnabled()) {
            $path = "noroute";
        }

        return $path;
    }
}
