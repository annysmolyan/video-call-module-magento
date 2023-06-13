<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Repository;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterfaceFactory;
use BelSmol\VideoCall\Api\Data\ManagerSearchResultsInterface;
use BelSmol\VideoCall\Api\Data\ManagerSearchResultsInterfaceFactory;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Model\ResourceModel\Manager\CollectionFactory;
use BelSmol\VideoCall\Model\ResourceModel\Manager as ResourceModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ManagerRepository
 * @package BelSmol\VideoCall\Model\Repository
 */
class ManagerRepository implements ManagerRepositoryInterface
{
    private ResourceModel $resourceModel;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;
    private ManagerSearchResultsInterfaceFactory $searchResultsFactory;
    private ManagerInterfaceFactory $managerFactory;

    /**
     * @param ResourceModel $resourceModel
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param ManagerSearchResultsInterfaceFactory $searchResultsFactory
     * @param ManagerInterfaceFactory $managerFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        ManagerSearchResultsInterfaceFactory $searchResultsFactory,
        ManagerInterfaceFactory $managerFactory
    ) {
        $this->resourceModel = $resourceModel;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->managerFactory = $managerFactory;
    }

    /**
     * Create or update a data
     *
     * @param ManagerInterface $manager
     * @return ManagerInterface
     * @throws CouldNotSaveException
     */
    public function save(ManagerInterface $manager): ManagerInterface
    {
        try {
            $this->resourceModel->save($manager);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save Manager: %1', $exception->getMessage()),
                $exception
            );
        }
        return $manager;
    }

    /**
     * Get by id
     *
     * @param int $id
     * @return ManagerInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ManagerInterface
    {
        $manager = $this->managerFactory->create();
        $manager->load($id);

        if (!$manager->getId()) {
            throw new NoSuchEntityException(__('Manager with the "%1" ID doesn\'t exist.', $id));
        }

        return $manager;
    }

    /**
     * Delete item.
     *
     * @param ManagerInterface $manager
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ManagerInterface $manager): bool
    {
        try {
            $this->resourceModel->delete($manager);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete Manager: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete by ID
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Get list by search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return ManagerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): ManagerSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();

        $collection->joinWebsiteRelationTable();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
