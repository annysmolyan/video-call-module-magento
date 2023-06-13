<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Video;

use BelSmol\VideoCall\Api\CallHistoryOperatorInterface;
use BelSmol\VideoCall\Api\CallRoomOperatorInterface;
use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\Data\CallRoomInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class SaveHistory
 * @package BelSmol\VideoCall\Controller\Video
 */
class SaveHistory implements HttpPostActionInterface
{
    protected const POST_PARAM_DURATION_ID = "duration";
    protected const MSG_NOT_FOUND = "Can't find SaveHistory controller";

    private RequestInterface $request;
    private ConfigHelper $configHelper;
    private JsonFactory $jsonFactory;
    private CallRoomOperatorInterface $callRoomOperator;
    private CallHistoryOperatorInterface $callHistoryOperator;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ConfigHelper $configHelper
     * @param CallHistoryOperatorInterface $callHistoryOperator
     * @param CallRoomOperatorInterface $callRoomOperator
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ConfigHelper $configHelper,
        CallHistoryOperatorInterface $callHistoryOperator,
        CallRoomOperatorInterface $callRoomOperator
    ) {
        $this->request = $context->getRequest();
        $this->jsonFactory = $jsonFactory;
        $this->configHelper = $configHelper;
        $this->callRoomOperator = $callRoomOperator;
        $this->callHistoryOperator = $callHistoryOperator;
    }

    /**
     * Remove room from DB
     * and save call history after hang up
     * @return ResultInterface
     * @throws NotFoundException
     */
    public function execute(): ResultInterface
    {
        if (!$this->configHelper->isModuleEnabled()) {
            throw new NotFoundException(self::MSG_NOT_FOUND);
        }

        $roomId = $this->request->getParam(ConstantInterface::REQUEST_ROOM_ID_PARAM, "");
        $duration = $this->request->getParam(self::POST_PARAM_DURATION_ID, "");

        $searchCriteriaBuilder = $this->callRoomOperator->initSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter(CallRoomInterface::ROOM_ID, $roomId);

        $room = $this->callRoomOperator->getRoomByParams($searchCriteriaBuilder);

        if (!$room->getManagerId() || !$room->getCustomerId()) {
            $this->callRoomOperator->delete($room);
            return $this->jsonFactory->create();
        }

        $callHistory = $this->callHistoryOperator->initNewHistory();

        $callHistory->setManagerId($room->getManagerId());
        $callHistory->setCustomerId($room->getCustomerId());
        $callHistory->setDuration($duration);

        $this->callHistoryOperator->save($callHistory);
        $this->callRoomOperator->delete($room);

        return $this->jsonFactory->create();
    }
}
