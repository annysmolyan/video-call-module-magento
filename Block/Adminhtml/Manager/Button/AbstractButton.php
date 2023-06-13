<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Block\Adminhtml\Manager\Button;

use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\UiComponentButtonInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class GenericButton
 * @package BelSmol\VideoCall\Block\Adminhtml\Manager\Button
 */
abstract class AbstractButton implements ButtonProviderInterface, UiComponentButtonInterface
{
    protected Registry $registry;
    protected UrlInterface $urlBuilder;

    /**
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    /**
     * @return int|null
     */
    public function getManagerId(): ?int
    {
        $manager = $this->registry->registry(ConstantInterface::BACKEND_REGISTRY_MANAGER);
        return $manager ? $manager->getId() : null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
