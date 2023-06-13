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
use BelSmol\VideoCall\Api\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ManagerTopLinksViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class ManagerTopLinksViewModel implements ArgumentInterface
{
    protected AuthHandlerInterface $authHandler;
    protected UrlInterface $url;

    /**
     * @param AuthHandlerInterface $authHandler
     * @param UrlInterface $url
     */
    public function __construct(
        AuthHandlerInterface $authHandler,
        UrlInterface $url
    ) {
        $this->authHandler = $authHandler;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getWelcomeMessage(): string
    {
        $manager = $this->authHandler->getUser();
        return __('Welcome, %1', $manager->getFullName());
    }

    /**
     * @return string
     */
    public function getLogoutUrl(): string
    {
        return $this->url->getManagerLogoutUrl();
    }
}
