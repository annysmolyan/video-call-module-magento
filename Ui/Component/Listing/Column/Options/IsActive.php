<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\Column\Options;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 * Options for select filter
 * @package BelSmol\VideoCall\Ui\Component\Listing\Column\Options
 */
class IsActive implements OptionSourceInterface
{
    protected const VALUE_KEY = "value";
    protected const LABEL_KEY = "label";

    protected const VALUE_IS_ACTIVE = 1;
    protected const VALUE_IS_NOT_ACTIVE = 0;

    protected const LABEL_IS_ACTIVE = 'Yes';
    protected const LABEL_IS_NOT_ACTIVE = 'No';

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        $result = [];
        $optionArray = $this->getOptionArray();

        foreach ($optionArray as $index => $value) {
            $result[] = [
                self::VALUE_KEY => $index,
                self::LABEL_KEY => $value
            ];
        }

        return $result;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray(): array
    {
        return [
            self::VALUE_IS_ACTIVE => __(self::LABEL_IS_ACTIVE),
            self::VALUE_IS_NOT_ACTIVE => __(self::LABEL_IS_NOT_ACTIVE)
        ];
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        $result = [];

        foreach ($this->getOptionArray() as $index => $value) {
            $result[] = [
                self::VALUE_KEY => $index,
                self::LABEL_KEY => $value
            ];
        }

        return $result;
    }
}
