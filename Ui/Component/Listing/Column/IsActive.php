<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class IsActive
 * column for manager_list UI component
 * @package BelSmol\VideoCall\Ui\Component\Listing\Column
 */
class IsActive extends Column
{
    protected const INDEX_DATA = "data";
    protected const INDEX_ITEMS = "items";
    protected const INDEX_FIELD_NAME = "name";

    protected const SOURCE_VALUE_IS_ACTIVE = '1';
    protected const LABEL_IS_ACTIVE = 'Yes';
    protected const LABEL_IS_NOT_ACTIVE = 'No';

    /**
     * Prepare data source.
     * Replace bool values from DB with labels
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource[self::INDEX_DATA][self::INDEX_ITEMS])) {

            $fieldName = $this->getData(self::INDEX_FIELD_NAME);

            foreach ($dataSource[self::INDEX_DATA][self::INDEX_ITEMS] as &$item) {
                if (isset($item[$fieldName]) && $item[$fieldName] == self::SOURCE_VALUE_IS_ACTIVE) {
                    $item[$fieldName] = __(self::LABEL_IS_ACTIVE);
                } else {
                    $item[$fieldName] = __(self::LABEL_IS_NOT_ACTIVE);
                }
            }
        }

        return $dataSource;
    }
}
