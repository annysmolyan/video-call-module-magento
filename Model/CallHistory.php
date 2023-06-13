<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model;

use BelSmol\VideoCall\Api\Data\CallHistoryInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Model\ResourceModel\CallHistory as ResourceModel;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class CallHistory
 * @package BelSmol\VideoCall\Model
 */
class CallHistory extends AbstractModel implements IdentityInterface, CallHistoryInterface
{
    const CACHE_TAG = "belsmol_videocall_manager_call_history";

    protected $_eventPrefix = "belsmol_videocall_manager_call_history_event";
    protected CustomerRepositoryInterface $customerRepository;
    protected ManagerRepositoryInterface $managerRepository;

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CustomerRepositoryInterface $customerRepository
     * @param ManagerRepositoryInterface $managerRepository
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CustomerRepositoryInterface $customerRepository,
        ManagerRepositoryInterface $managerRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->customerRepository = $customerRepository;
        $this->managerRepository = $managerRepository;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int|null
     */
    public function getManagerId(): ?int
    {
        return $this->getData(self::MANAGER_ID);
    }

    /**
     * @param int $id
     */
    public function setManagerId(int $id): void
    {
        $this->setData(self::MANAGER_ID, $id);
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param int $id
     */
    public function setCustomerId(int $id): void
    {
        $this->setData(self::CUSTOMER_ID, $id);
    }

    /**
     * In seconds
     * @return int
     */
    public function getDuration(): int
    {
        return (int)$this->getData(self::DURATION);
    }

    /**
     * In seconds
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->setData(self::DURATION, $duration);
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->getData(self::DATE);
    }

    /**
     * @param string $date
     */
    public function setDate(string $date = ""): void
    {
        $this->getData(self::DATE, $date);
    }

    /**
     * @return CustomerInterface|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomer(): ?CustomerInterface
    {
        $customer = $this->getData(self::CUSTOMER);
        $customerId = $this->getCustomerId();

        if ($customer === null && $customerId) {
            $customer = $this->customerRepository->getById($customerId);
            $this->setData(self::CUSTOMER, $customer);
        }

        return $customer;
    }

    /**
     * @return ManagerInterface|null
     */
    public function getManager(): ?ManagerInterface
    {
        $manager = $this->getData(self::MANAGER);
        $managerId = $this->getCustomerId();

        if ($manager === null && $managerId) {
            $manager = $this->managerRepository->getById($managerId);
            $this->setData(self::MANAGER, $manager);
        }

        return $manager;
    }
}
