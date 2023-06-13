<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Manager\Ajax;

use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\CallRoomOperatorInterface;
use BelSmol\VideoCall\Api\CustomerOperatorInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerAccountControllerInterface;
use BelSmol\VideoCall\Api\UrlInterface;
use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class CallList
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager\Ajax
 */
class CallList implements HttpPostActionInterface, ManagerAccountControllerInterface
{
    protected CallRoomOperatorInterface $callRoomOperator;
    private AuthHandlerInterface $managerAuthHandler;
    private UrlInterface $urlModel;
    private JsonFactory $jsonFactory;
    private RequestInterface $request;
    private CustomerOperatorInterface $customerOperator;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CallRoomOperatorInterface $callRoomOperator
     * @param AuthHandlerInterface $managerAuthHandler
     * @param UrlInterface $urlModel
     * @param CustomerOperatorInterface $customerOperator
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        CallRoomOperatorInterface $callRoomOperator,
        AuthHandlerInterface $managerAuthHandler,
        UrlInterface $urlModel,
        CustomerOperatorInterface $customerOperator
    ) {
        $this->callRoomOperator = $callRoomOperator;
        $this->managerAuthHandler = $managerAuthHandler;
        $this->urlModel = $urlModel;
        $this->jsonFactory = $jsonFactory;
        $this->request = $context->getRequest();
        $this->customerOperator = $customerOperator;
    }

    /**
     * Return list of calls for logged in manager
     * @return ResultInterface
     * @throws Exception
     */
    public function execute(): ResultInterface
    {
        if (!$this->request->isAjax()) {
            throw new Exception('Access Denied', 403);
        }

        $manager = $this->managerAuthHandler->getUser();
        $managerWebsites = $manager->getWebsites();

        $searchCriteriaBuilder = $this->callRoomOperator->initSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter(CallRoomInterface::MANAGER_ID, null, "null");
        $searchCriteriaBuilder->addFilter(
            CallRoomInterface::WEBSITE_ID,
            implode(',', $managerWebsites),
            "in"
        );

        $searchResult = $this->callRoomOperator->getRoomsByParam($searchCriteriaBuilder);
        $list = [];

        foreach ($searchResult as $room) {
            $customer = $this->customerOperator->getCustomerById($room->getCustomerId());
            $list[] = [
                "id" => $room->getId(),
                "customer" => $customer->getFirstname() . " " . $customer->getLastname(),
                "customerEmail" => $customer->getEmail(),
                "url" => $this->getCallUrl($manager, $room),
            ];
        }

        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData(["callList" => $list]);
    }

    /**
     * @param ManagerInterface $manager
     * @param CallRoomInterface $room
     * @return string
     */
    private function getCallUrl(ManagerInterface $manager, CallRoomInterface $room): string
    {
        return $this->urlModel->getCustomerVideoCallUrl([
            "room" => $room->getRoomId(),
            "token" => $manager->getToken(),
        ]);
    }
}
