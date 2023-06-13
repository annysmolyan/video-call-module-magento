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
use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Api\ManagerOperatorInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Call
 * @package BelSmol\VideoCall\Controller\Video
 */
class Call implements HttpGetActionInterface
{
    protected const POST_PARAM_TOKEN = "token";

    private PageFactory $resultPageFactory;
    private ConfigHelper $configHelper;
    private RedirectFactory $resultRedirectFactory;
    private RequestInterface $request;
    private ManagerOperatorInterface $managerOperator;
    private CallRoomOperatorInterface $callRoomOperator;
    private CustomerOperatorInterface $customerOperator;

    /**
     * @param PageFactory $resultPageFactory
     * @param ConfigHelper $configHelper
     * @param Context $context
     * @param ManagerOperatorInterface $managerOperator
     * @param CallRoomOperatorInterface $callRoomOperator
     * @param CustomerOperatorInterface $customerOperator
     */
    public function __construct(
        PageFactory $resultPageFactory,
        ConfigHelper $configHelper,
        Context $context,
        ManagerOperatorInterface $managerOperator,
        CallRoomOperatorInterface $callRoomOperator,
        CustomerOperatorInterface $customerOperator
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->configHelper = $configHelper;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->request = $context->getRequest();
        $this->managerOperator = $managerOperator;
        $this->callRoomOperator = $callRoomOperator;
        $this->customerOperator = $customerOperator;
    }

    /**
     * Video call controller.
     * Check that customer or manager is allowed to join call.
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $roomId = $this->request->getParam(ConstantInterface::REQUEST_ROOM_ID_PARAM, "");
        $managerToken = $this->request->getParam(self::POST_PARAM_TOKEN, "");

        $searchCriteriaBuilder = $this->callRoomOperator->initSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter(CallRoomInterface::ROOM_ID, $roomId);
        $room = $this->callRoomOperator->getRoomByParams($searchCriteriaBuilder);

        if (!$this->configHelper->isModuleEnabled() || !$room) {
            return $this->redirectToPage("noroute");
        }

        if ($managerToken) {
            if ($managerRedirectPath = $this->getManagerRedirectPath($managerToken, $room)) {
                return $this->redirectToPage($managerRedirectPath);
            }

            $manager = $this->managerOperator->getLoggedInManagerByToken($managerToken);

            if ($room->getManagerId() !== (int)$manager->getId()) {
                $room->setManagerId((int)$manager->getId());
                $this->callRoomOperator->save($room);
            }
        } elseif ($customerRedirectPath = $this->getCustomerRedirectPath($room)) {
            return $this->redirectToPage($customerRedirectPath);
        }

        return $this->resultPageFactory->create();
    }

    /**
     * @param string $token
     * @param CallRoomInterface $room
     * @return string
     */
    private function getManagerRedirectPath(string $token, CallRoomInterface $room): string
    {
        $redirectPath = "";
        $manager = $this->managerOperator->getLoggedInManagerByToken($token);

        if (!$manager) {
            $redirectPath = "videocall/manager/login";
        } elseif ($room->getManagerId() && ($room->getManagerId() !== (int)$manager->getId())) {
            $redirectPath = "noroute";
        }

        return $redirectPath;
    }

    /**
     * @param CallRoomInterface $room
     * @return string
     */
    private function getCustomerRedirectPath(CallRoomInterface $room): string
    {
        $redirectPath = "";
        $customerId = $this->customerOperator->getLoggedInCustomerId();

        if (!$customerId) {
            $redirectPath = "customer/account/login";
        } elseif ($room->getCustomerId() != $customerId) {
            $redirectPath = "noroute";
        }

        return $redirectPath;
    }

    /**
     * @param string $page
     * @return ResultInterface
     */
    private function redirectToPage(string $page): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($page);
    }
}
