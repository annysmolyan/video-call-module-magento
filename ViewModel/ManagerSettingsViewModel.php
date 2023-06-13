<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\ViewModel;

use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ManagerSettingsViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class ManagerSettingsViewModel implements ArgumentInterface
{
    protected UrlInterface $url;
    protected AuthHandlerInterface $authHandler;

    /**
     * @param UrlInterface $url
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(
        UrlInterface $url,
        AuthHandlerInterface $authHandler
    ) {
        $this->url = $url;
        $this->authHandler = $authHandler;
    }

    /**
     * @return ManagerInterface|null
     */
    public function getManager(): ?ManagerInterface
    {
        return $this->authHandler->getUser();
    }

    /**
     * @return string
     */
    public function getSecurityActionForm(): string
    {
        return $this->url->getManagerSecurityPostUrl();
    }

    /**
     * @return string
     */
    public function getProfileActionForm(): string
    {
        return $this->url->getManagerProfilePostUrl();
    }
}
