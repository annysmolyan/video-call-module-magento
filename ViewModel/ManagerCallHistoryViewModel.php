<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\ViewModel;

use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\CallHistoryOperatorInterface;
use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Traits\FormatterTrait;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ManagerCallHistoryViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class ManagerCallHistoryViewModel implements ArgumentInterface
{
    use FormatterTrait;

    protected AuthHandlerInterface $authHandler;
    protected CallHistoryOperatorInterface $callHistoryOperator;

    /**
     * @param CallHistoryOperatorInterface $callHistoryOperator
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(
        CallHistoryOperatorInterface $callHistoryOperator,
        AuthHandlerInterface $authHandler
    ) {
        $this->authHandler = $authHandler;
        $this->callHistoryOperator = $callHistoryOperator;
    }

    /**
     * @return CallHistoryInterface[]
     */
    public function getManagerHistory(): array
    {
        $manager = $this->getManager();
        $searchCriteriaBuilder = $this->callHistoryOperator->initSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter(CallHistoryInterface::MANAGER_ID, $manager->getId());
        return $this->callHistoryOperator->getList($searchCriteriaBuilder);
    }

    /**
     * Return current logged in manager id
     * @return ManagerInterface|null
     */
    protected function getManager(): ?ManagerInterface
    {
        return $this->authHandler->getUser();
    }
}
