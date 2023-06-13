<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Operator;

use BelSmol\VideoCall\Api\CallRoomOperatorInterface;
use BelSmol\VideoCall\Api\CallRoomRepositoryInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;

/**
 * Class CallRoomOperator
 * @package BelSmol\VideoCall\Model\Operator
 */
class CallRoomOperator implements CallRoomOperatorInterface
{
    protected SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;
    protected CallRoomRepositoryInterface $callRoomRepository;
    protected CallRoomInterfaceFactory $callRoomInterfaceFactory;

    /**
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param CallRoomRepositoryInterface $callRoomRepository
     * @param CallRoomInterfaceFactory $callRoomInterfaceFactory
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CallRoomRepositoryInterface $callRoomRepository,
        CallRoomInterfaceFactory $callRoomInterfaceFactory
    ) {
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->callRoomRepository = $callRoomRepository;
        $this->callRoomInterfaceFactory = $callRoomInterfaceFactory;
    }

    /**
     * @return CallRoomInterface
     */
    public function initNewRoom(): CallRoomInterface
    {
        return $this->callRoomInterfaceFactory->create();
    }

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return CallRoomInterface|null
     */
    public function getRoomByParams(SearchCriteriaBuilder $searchCriteriaBuilder): ?CallRoomInterface
    {
        $searchResult = $this->getRoomsByParam($searchCriteriaBuilder);
        return $searchResult ? reset($searchResult) : null;
    }

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return array
     */
    public function getRoomsByParam(SearchCriteriaBuilder $searchCriteriaBuilder): array
    {
        $searchCriteria = $searchCriteriaBuilder->create();
        return $this->callRoomRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @return SearchCriteriaBuilder
     */
    public function initSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilderFactory->create();
    }

    /**
     * @param CallRoomInterface $room
     */
    public function save(CallRoomInterface $room): void
    {
        $this->callRoomRepository->save($room);
    }

    /**
     * @param CallRoomInterface $room
     */
    public function delete(CallRoomInterface $room): void
    {
        $this->callRoomRepository->delete($room);
    }
}
