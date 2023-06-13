<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\Manager;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Model\ResourceModel\Manager\Collection;
use BelSmol\VideoCall\Model\ResourceModel\Manager\CollectionFactory;

/**
 * Class AbstractMassAction
 * Abstract class for Manager mass actions
 * @package BelSmol\VideoCall\Controller\Adminhtml\Manager
 */
abstract class AbstractMassAction extends Action
{
    protected Filter $massActionFilter;
    protected CollectionFactory $collectionFactory;
    protected ManagerRepositoryInterface $managerRepository;

    /**
     * @param Context $context
     * @param Filter $massActionFilter
     * @param CollectionFactory $collectionFactory
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        Context $context,
        Filter $massActionFilter,
        CollectionFactory $collectionFactory,
        ManagerRepositoryInterface $managerRepository
    ) {
        parent::__construct($context);
        $this->massActionFilter = $massActionFilter;
        $this->collectionFactory = $collectionFactory;
        $this->managerRepository = $managerRepository;
    }

    /**
     * @return Collection
     * @throws LocalizedException
     */
    protected function loadCollection(): Collection
    {
        return $this->massActionFilter->getCollection($this->collectionFactory->create());
    }

    /**
     * @return ResultInterface
     */
    abstract public function execute(): ResultInterface;
}
