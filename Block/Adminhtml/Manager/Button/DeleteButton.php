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
 * Class DeleteButton
 * @package BelSmol\VideoCall\Block\Adminhtml\Manager\Button
 */
class DeleteButton extends AbstractButton
{
    const BTN_LABEL = "Delete";

    /**
     * Retrieve button-specified settings
     * @return array
     */
    public function getButtonData(): array
    {
        $id = $this->getManagerId();
        $data = [];

        if ($id) {
            $data = [
                self::LABEL_KEY => __(self::BTN_LABEL),
                self::CLASS_KEY => 'delete',
                self::ON_CLICK_KEY => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getUrl('*/*/delete', ['id' => $id]) . '\', {"data": {}})',
                self::SORT_ORDER_KEY => 20,
            ];
        }
        return $data;
    }
}
