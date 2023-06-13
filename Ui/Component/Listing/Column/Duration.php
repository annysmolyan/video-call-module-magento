<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\Column;

use BelSmol\VideoCall\Traits\FormatterTrait;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Duration
 * @package BelSmol\VideoCall\Ui\Component\Listing
 */
class Duration extends Column
{
    use FormatterTrait;

    protected const INDEX_DATA = "data";
    protected const INDEX_ITEMS = "items";
    protected const INDEX_FIELD_NAME = "name";

    /**
     * Prepare data source.
     * Replace int values from DB with hrs:mm:sec formatted string
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource[self::INDEX_DATA][self::INDEX_ITEMS])) {

            $fieldName = $this->getData(self::INDEX_FIELD_NAME);

            foreach ($dataSource[self::INDEX_DATA][self::INDEX_ITEMS] as &$item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $this->formatDuration((int)$item[$fieldName]);
                }
            }
        }
        return $dataSource;
    }
}
