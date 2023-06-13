<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Repository;

use BelSmol\VideoCall\Api\CallRoomRepositoryInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterfaceFactory;
use BelSmol\VideoCall\Api\Data\CallRoomSearchResultInterface;
use BelSmol\VideoCall\Api\Data\CallRoomSearchResultInterfaceFactory;
use BelSmol\VideoCall\Model\ResourceModel\CallRoom as ResourceModel;
use BelSmol\VideoCall\Model\ResourceModel\CallRoom\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CallRoomRepository
 * @package BelSmol\VideoCall\Model\Repository
 */
class CallRoomRepository implements CallRoomRepositoryInterface
{
    private ResourceModel $resourceModel;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;
    private CallRoomSearchResultInterfaceFactory $searchResultFactory;
    private CallRoomInterfaceFactory $callRoomFactory;

    /**
     * @param ResourceModel $resourceModel
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param CallRoomSearchResultInterfaceFactory $callRoomSearchResultFactory
     * @param CallRoomInterfaceFactory $callRoomFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        CallRoomSearchResultInterfaceFactory $callRoomSearchResultFactory,
        CallRoomInterfaceFactory $callRoomFactory
    ) {

        $this->resourceModel = $resourceModel;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $callRoomSearchResultFactory;
        $this->callRoomFactory = $callRoomFactory;
    }

    /**
     * Create or update a data
     *
     * @param CallRoomInterface $room
     * @return CallRoomInterface
     * @throws CouldNotSaveException
     */
    public function save(CallRoomInterface $room): CallRoomInterface
    {
        try {
            $this->resourceModel->save($room);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save Room: %1', $exception->getMessage()),
                $exception
            );
        }
        return $room;
    }

    /**
     * Get by id
     *
     * @param int $id
     * @return CallRoomInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CallRoomInterface
    {
        $room = $this->callRoomFactory->create();
        $room->load($id);

        if (!$room->getId()) {
            throw new NoSuchEntityException(__('Room with the "%1" ID doesn\'t exist.', $id));
        }

        return $room;
    }

    /**
     * Delete item.
     *
     * @param CallRoomInterface $room
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CallRoomInterface $room): bool
    {
        try {
            $this->resourceModel->delete($room);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete room: %1',
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
     * @return CallRoomSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $criteria): CallRoomSearchResultInterface
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
