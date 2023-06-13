<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Video;

use BelSmol\VideoCall\Api\CallRoomOperatorInterface;
use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\CustomerOperatorInterface;
use BelSmol\VideoCall\Api\ManagerOperatorInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class InitRoom
 * Init room for meeting
 * @package BelSmol\VideoCall\Controller\VideoCall
 */
class InitRoom implements HttpPostActionInterface
{
    protected const PARAM_CUSTOMER_ID = "customer_id";
    protected const PARAM_MANAGER_ID = "manager_id";

    protected const MSG_WRONG_CUSTOMER_SESSION = "Wrong customer session!";
    protected const MSG_WRONG_USERS = "Manager or customer doesn't exists!";
    protected const MSG_NOT_FOUND = "Can't find InitRoom controller";

    private ConfigHelper $configHelper;
    private JsonFactory $jsonFactory;
    private RequestInterface $request;
    private CallRoomOperatorInterface $callRoomOperator;
    private CustomerOperatorInterface $customerOperator;
    private ManagerOperatorInterface $managerOperator;

    /**
     * @param ConfigHelper $configHelper
     * @param JsonFactory $jsonFactory
     * @param Context $context
     * @param ManagerOperatorInterface $managerOperator
     * @param CallRoomOperatorInterface $callRoomOperator
     * @param CustomerOperatorInterface $customerOperator
     */
    public function __construct(
        ConfigHelper $configHelper,
        JsonFactory $jsonFactory,
        Context $context,
        ManagerOperatorInterface $managerOperator,
        CallRoomOperatorInterface $callRoomOperator,
        CustomerOperatorInterface $customerOperator
    ) {
        $this->configHelper = $configHelper;
        $this->jsonFactory = $jsonFactory;
        $this->request = $context->getRequest();
        $this->callRoomOperator = $callRoomOperator;
        $this->customerOperator = $customerOperator;
        $this->managerOperator = $managerOperator;
    }

    /**
     * @return ResultInterface
     * @throws NotFoundException
     */
    public function execute(): ResultInterface
    {
        if (!$this->configHelper->isModuleEnabled()) {
            throw new NotFoundException(self::MSG_NOT_FOUND);
        }

        $customerId = (int)$this->request->getParam(self::PARAM_CUSTOMER_ID);
        $managerId = (int)$this->request->getParam(self::PARAM_MANAGER_ID);

        if ($managerId) {
            if (!$this->areUsersValid($managerId, $customerId)) {
                throw new Exception(self::MSG_WRONG_USERS);
            }
        } elseif (!$this->customerOperator->isCustomerSessionValid($customerId)) {
            throw new Exception(self::MSG_WRONG_CUSTOMER_SESSION);
        }

        $customer = $this->customerOperator->getCustomerById($customerId);
        $room = $this->callRoomOperator->initNewRoom();
        $roomId = $customerId . '_' . time();
        $room->setCustomerId($customerId);
        $room->setRoomId($roomId);
        $room->setWebsiteId((int)$customer->getWebsiteId());

        if ($managerId) {
            $room->setManagerId($managerId);
        }

        $this->callRoomOperator->save($room);
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData([ConstantInterface::REQUEST_ROOM_ID_PARAM => $roomId]);
    }

    /**
     * @param int $managerId
     * @param int $customerId
     * @return bool
     */
    private function areUsersValid(int $managerId, int $customerId): bool
    {
        return $this->customerOperator->getCustomerById($customerId)
            && $this->managerOperator->isManagerIdExists($managerId);
    }
}
