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
 * Class SaveButton
 * @package BelSmol\VideoCall\Block\Adminhtml\Manager\Button
 */
class SaveButton extends AbstractButton
{
    const BTN_LABEL = "Save";

    /**
     * Retrieve button-specified settings
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            self::LABEL_KEY => __(self::BTN_LABEL),
            self::CLASS_KEY => 'save primary',
            self::DATA_ATTRIBUTE_KEY => [
                'mage-init' => [
                    'button' => ['event' => 'save']
                ],
                'form-role' => 'save',
            ],
            self::SORT_ORDER_KEY => 100,
        ];
    }
}
