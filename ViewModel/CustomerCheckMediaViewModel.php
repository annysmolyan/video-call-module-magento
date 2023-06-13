<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\ViewModel;

use BelSmol\VideoCall\Helper\ConfigHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class CustomerCheckMediaViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class CustomerCheckMediaViewModel implements ArgumentInterface
{
    protected ConfigHelper $configHelper;
    protected Session $session;

    /**
     * @param ConfigHelper $configHelper
     * @param Session $session
     */
    public function __construct(
        ConfigHelper $configHelper,
        Session $session
    ) {
        $this->configHelper = $configHelper;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        return $this->configHelper->getSupportEmail();
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->session->getCustomerId();
    }

    /**
     * @return string
     */
    public function getWebrtcAdapter(): string
    {
        return $this->configHelper->getWebrtcAdapter();
    }
}
