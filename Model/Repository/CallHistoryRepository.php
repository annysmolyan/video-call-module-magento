<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Repository;

use BelSmol\VideoCall\Api\CallHistoryRepositoryInterface;
use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Api\Data\CallHistoryInterfaceFactory;
use BelSmol\VideoCall\Api\Data\CallHistorySearchResultsInterface;
use BelSmol\VideoCall\Api\Data\CallHistorySearchResultsInterfaceFactory;
use BelSmol\VideoCall\Model\ResourceModel\CallHistory as ResourceModel;
use BelSmol\VideoCall\Model\ResourceModel\CallHistory\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CallHistoryRepository
 * @package BelSmol\VideoCall\Model\Repository
 */
class CallHistoryRepository implements CallHistoryRepositoryInterface
{
    private ResourceModel $resourceModel;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;
    private CallHistorySearchResultsInterfaceFactory $searchResultsFactory;
    private CallHistoryInterfaceFactory $callHistoryFactory;

    /**
     * @param ResourceModel $resourceModel
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param CallHistorySearchResultsInterfaceFactory $callHistorySearchResultsFactory
     * @param CallHistoryInterfaceFactory $callHistoryFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        CallHistorySearchResultsInterfaceFactory $callHistorySearchResultsFactory,
        CallHistoryInterfaceFactory $callHistoryFactory
    ) {
        $this->resourceModel = $resourceModel;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $callHistorySearchResultsFactory;
        $this->callHistoryFactory = $callHistoryFactory;
    }

    /**
     * @param CallHistoryInterface $history
     * @return CallHistoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CallHistoryInterface $history): CallHistoryInterface
    {
        try {
            $this->resourceModel->save($history);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save call history row: %1', $exception->getMessage()),
                $exception
            );
        }
        return $history;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param CallHistoryInterface $history
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CallHistoryInterface $history): bool
    {
        try {
            $this->resourceModel->delete($history);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete History row: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @param int $id
     * @return CallHistoryInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CallHistoryInterface
    {
        $history = $this->callHistoryFactory->create();
        $history->load($id);

        if (!$history->getId()) {
            throw new NoSuchEntityException(__('There is no history for row ID = "%1" ', $id));
        }

        return $history;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return CallHistorySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): CallHistorySearchResultsInterface
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
