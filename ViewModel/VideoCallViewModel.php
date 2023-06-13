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
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class CustomerVideoCallViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class VideoCallViewModel implements ArgumentInterface
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
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->session->getCustomer();
    }

    /**
     * @return string
     */
    public function getWebrtcAdapter(): string
    {
        return $this->configHelper->getWebrtcAdapter();
    }

    /**
     * @return string
     */
    public function getSignalingServerAddress(): string
    {
        return $this->configHelper->getSignalingServerHost() . ':' . $this->configHelper->getSignalingServerPort();
    }

    /**
     * @return string
     */
    public function getIceServers(): string
    {
        $config = $this->configHelper->getIceServers();
        return json_encode($config);
    }
}
