<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Block\Adminhtml\Manager\Button;

/**
 * Class BackButton
 * @package BelSmol\VideoCall\Block\Adminhtml\Manager\Button
 */
class BackButton extends AbstractButton
{
    const BTN_LABEL = "Back";

    /**
     * Retrieve button-specified settings
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            self::LABEL_KEY => __(self::BTN_LABEL),
            self::ON_CLICK_KEY => sprintf("location.href = '%s';", $this->getUrl("*/*/index")),
            self::CLASS_KEY => 'back',
            self::SORT_ORDER_KEY => 10
        ];
    }
}
