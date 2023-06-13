<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\ActionColumn;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class AbstractAction
 *
 * Abstract action for UI component edit action.
 * If want to add edit action for another UI component just inherit this class.
 * You can also extend method prepareDataSource and add other actions if need
 *
 * @package BelSmol\VideoCall\Ui\Component\Listing\ActionColumn
 */
abstract class AbstractAction extends Column
{
    protected const INDEX_DATA = "data";
    protected const INDEX_ITEMS = "items";
    protected const INDEX_FIELD_NAME = "name";

    protected const ACTION_EDIT_NAME = "edit";

    protected const LABEL_ACTION_EDIT = "Edit";

    protected UrlInterface $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource[self::INDEX_DATA][self::INDEX_ITEMS])) {
            foreach ($dataSource[self::INDEX_DATA][self::INDEX_ITEMS] as &$item) {
                $this->addEditAction($item);
            }
        }

        return $dataSource;
    }

    /**
     * Add Edit Action to Row
     *
     * @param array $item
     * @return void
     */
    private function addEditAction(array &$item): void
    {
        $itemId = $item[$this->getEntityIdFieldName()];
        $editUrl = $this->getEditUrlPath();

        $item[$this->getData(self::INDEX_FIELD_NAME)][self::ACTION_EDIT_NAME] = [
            "href" => $this->urlBuilder->getUrl($editUrl, ["id" => $itemId]),
            "label" => __(self::LABEL_ACTION_EDIT),
            'hidden' => false,
        ];
    }

    /**
     * Return edit path
     *
     * @return string
     */
    abstract public function getEditUrlPath(): string;

    /**
     * Return edit path
     *
     * @return string
     */
    abstract public function getEntityIdFieldName(): string;
}
