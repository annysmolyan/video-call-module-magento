<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Block\Adminhtml\CallHistory\Button;

use BelSmol\VideoCall\Api\UiComponentButtonInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class TruncateButton
 * @package BelSmol\VideoCall\Block\Adminhtml\CallHistory\Button
 */
class TruncateButton implements ButtonProviderInterface, UiComponentButtonInterface
{
    const BUTTON_LABEL = 'Truncate Table';
    const BUTTON_CLASS = 'primary';
    const BUTTON_ORDER = 20;
    const BUTTON_DELETE_CONFIRM_MSG = 'Are you sure you want to do this? This action can not be undone.';

    const TRUNCATE_ROUTE = '*/*/truncate';

    protected UrlInterface $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            self::LABEL_KEY => __(self::BUTTON_LABEL),
            self::CLASS_KEY => self::BUTTON_CLASS,
            self::ON_CLICK_KEY => 'deleteConfirm(\'' . __(self::BUTTON_DELETE_CONFIRM_MSG) . '\', \'' . $this->getTruncateUrl() . '\')',
            self::SORT_ORDER_KEY => self::BUTTON_ORDER,
        ];
    }

    /**
     * @return string
     */
    protected function getTruncateUrl(): string
    {
        return $this->urlBuilder->getUrl(self::TRUNCATE_ROUTE);
    }
}
