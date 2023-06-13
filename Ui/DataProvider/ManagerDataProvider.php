<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\DataProvider;

use BelSmol\VideoCall\Api\AdminTabManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use BelSmol\VideoCall\Model\ResourceModel\Manager\CollectionFactory;

/**
 * Class ManagerDataProvider
 * Data provider for manager edit form
 * @package BelSmol\VideoCall\Ui\DataProvider
 */
class ManagerDataProvider extends AbstractDataProvider implements AdminTabManagerInterface
{
    protected array $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        $result = [];
        $items = $this->collection->getItems();

        foreach ($items as $manager) {
            $result[self::TAB_GENERAL] = $manager->getData();
            $result[self::TAB_WEBSITES] = [self::TAB_WEBSITES => $manager->getWebsites()];
            $result[self::TAB_CONNECTION] = [self::FIELD_TOKEN => $manager->getToken()];

            $this->loadedData[$manager->getId()] = $result;
        }

        return $this->loadedData;
    }
}
