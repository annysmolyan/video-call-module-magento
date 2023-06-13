<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Operator;

use BelSmol\VideoCall\Api\CustomerOperatorInterface;
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;

/**
 * Class CustomerOperator
 * @package BelSmol\VideoCall\Model\Operator
 */
class CustomerOperator implements CustomerOperatorInterface
{
    protected Session $session;
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @param Session $session
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(Session $session, CustomerRepositoryInterface $customerRepository)
    {
        $this->session = $session;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param int $customerId
     * @return bool
     */
    public function isCustomerSessionValid(int $customerId): bool
    {
        $customer = $this->session->getCustomer();
        return $customer && ($customer->getId() == $customerId);
    }

    /**
     * @return int|null
     */
    public function getLoggedInCustomerId(): ?int
    {
        return $this->session->getCustomerId();
    }

    /**
     * @param int $id
     * @return CustomerInterface|null
     */
    public function getCustomerById(int $id): ?CustomerInterface
    {
        try {
            $customer = $this->customerRepository->getById($id);
        } catch (Exception $exception) {
            $customer = null;
        }
        return $customer;
    }
}
