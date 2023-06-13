<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Operator;

use BelSmol\VideoCall\Api\CallHistoryOperatorInterface;
use BelSmol\VideoCall\Api\CallHistoryRepositoryInterface;
use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Api\Data\CallHistoryInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;

/**
 * Class CallHistoryOperator
 * @package BelSmol\VideoCall\Model\Operator
 */
class CallHistoryOperator implements CallHistoryOperatorInterface
{
    protected CallHistoryRepositoryInterface $callHistoryRepository;
    protected CallHistoryInterfaceFactory $callHistoryInterfaceFactory;
    protected SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    /**
     * @param CallHistoryRepositoryInterface $callHistoryRepository
     * @param CallHistoryInterfaceFactory $callHistoryInterfaceFactory
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        CallHistoryRepositoryInterface $callHistoryRepository,
        CallHistoryInterfaceFactory $callHistoryInterfaceFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->callHistoryRepository = $callHistoryRepository;
        $this->callHistoryInterfaceFactory = $callHistoryInterfaceFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * @return CallHistoryInterface
     */
    public function initNewHistory(): CallHistoryInterface
    {
        return $this->callHistoryInterfaceFactory->create();
    }

    /**
     * @param CallHistoryInterface $callHistory
     */
    public function save(CallHistoryInterface $callHistory): void
    {
        $this->callHistoryRepository->save($callHistory);
    }

    /**
     * @param CallHistoryInterface $callHistory
     */
    public function delete(CallHistoryInterface $callHistory): void
    {
        $this->callHistoryRepository->delete($callHistory);
    }

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return array|null
     */
    public function getList(SearchCriteriaBuilder $searchCriteriaBuilder): ?array
    {
        $searchCriteria = $searchCriteriaBuilder->create();
        return $this->callHistoryRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @return SearchCriteriaBuilder
     */
    public function initSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilderFactory->create();
    }
}
