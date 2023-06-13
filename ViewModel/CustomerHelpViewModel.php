<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\ViewModel;

use BelSmol\VideoCall\Api\UrlInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ManagerListViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class CustomerHelpViewModel implements ArgumentInterface
{
    protected ConfigHelper $configHelper;
    protected UrlInterface $urlModel;

    /**
     * @param ConfigHelper $configHelper
     * @param UrlInterface $urlModel
     */
    public function __construct(
        ConfigHelper $configHelper,
        UrlInterface $urlModel
    ) {
        $this->configHelper = $configHelper;
        $this->urlModel = $urlModel;
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        return $this->configHelper->getSupportEmail();
    }

    /**
     * @return string
     */
    public function getCheckMediaUrl(): string
    {
        return $this->urlModel->getCustomerCheckMediaUrl();
    }
}
