<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\Manager;

use BelSmol\VideoCall\Api\AdminTabManagerInterface;
use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterfaceFactory;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Api\PasswordServiceInterface;
use BelSmol\VideoCall\Api\TokenServiceInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Save
 * @package BelSmol\VideoCall\Controller\Adminhtml\Manager
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = "BelSmol_VideoCall::managerCreate";

    protected const REQUEST_PARAM_BACK = "back";

    protected const NO_SUCH_ENTITY_ERROR_MSG = "This page no longer exists.";
    protected const SERVER_ERROR_MSG = "Something went wrong while saving the page.";
    protected const SUCCESS_MSG = "The data has been saved.";

    protected const EVENT_PREPARE_SAVE_MANAGER = "belsmol_videocall_manager_prepare_save";
    protected const EVENT_COMPLETE_SAVE_MANAGER = "belsmol_videocall_manager_complete_save";
    protected const EVENT_SAVE_NEW_MANAGER = "belsmol_videocall_manager_before_new_save";

    private ManagerRepositoryInterface $managerRepository;
    private ManagerInterfaceFactory $managerFactory;
    private DataPersistorInterface $dataPersistor;
    private PasswordServiceInterface $passwordService;
    private TokenServiceInterface $tokenService;

    /**
     * @param Context $context
     * @param ManagerRepositoryInterface $managerRepository
     * @param ManagerInterfaceFactory $managerFactory
     * @param DataPersistorInterface $dataPersistor
     * @param PasswordServiceInterface $passwordService
     * @param TokenServiceInterface $tokenService
     */
    public function __construct(
        Context $context,
        ManagerRepositoryInterface $managerRepository,
        ManagerInterfaceFactory $managerFactory,
        DataPersistorInterface $dataPersistor,
        PasswordServiceInterface $passwordService,
        TokenServiceInterface $tokenService
    ) {
        parent::__construct($context);
        $this->managerRepository = $managerRepository;
        $this->managerFactory = $managerFactory;
        $this->dataPersistor = $dataPersistor;
        $this->passwordService = $passwordService;
        $this->tokenService = $tokenService;
    }

    /**
     * Execute action based on request and return result
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $postValue = $this->getRequest()->getPostValue();

        if (!$postValue) {
            return $resultRedirect->setPath('*/*/');
        }

        $data = $this->createDataFromPost($postValue);
        $managerId = $data[ManagerInterface::ENTITY_ID] ?? null;
        $manager = $this->getManager((int)$managerId);

        if (null == $manager) {
            $this->messageManager->addErrorMessage(__(self::NO_SUCH_ENTITY_ERROR_MSG));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $manager->setData($data);
            $this->dispatchEvent($manager, self::EVENT_PREPARE_SAVE_MANAGER);
            $this->managerRepository->save($manager);
            $this->dispatchEvent($manager, self::EVENT_COMPLETE_SAVE_MANAGER);
            $this->messageManager->addSuccessMessage(__(self::SUCCESS_MSG));
            return $this->processResultRedirect($manager, $resultRedirect);
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __(self::SERVER_ERROR_MSG));
        }

        $this->dataPersistor->set(ConstantInterface::BACKEND_REGISTRY_MANAGER, $data);
        return $resultRedirect->setPath('*/*/edit', ['id' => $manager->getId()]);
    }

    /**
     * Create manager data according to a post data
     * @param array $postValue
     * @return array
     */
    private function createDataFromPost(array $postValue): array
    {
        $data = $postValue[AdminTabManagerInterface::TAB_GENERAL];
        $data[AdminTabManagerInterface::TAB_WEBSITES] =
            $postValue[AdminTabManagerInterface::TAB_WEBSITES][AdminTabManagerInterface::TAB_WEBSITES];

        if (!isset($data[ManagerInterface::ENTITY_ID])) {
            $password = $this->passwordService->generateRandomPassword();
            $data["password"] = $password;
            $data[ManagerInterface::PASSWORD_HASH] = $this->passwordService->createPasswordHash($password);
            $data[ManagerInterface::TOKEN] = $this->tokenService->generateToken();
        }
        return $data;
    }

    /**
     * @param int $id
     * @return ManagerInterface|null
     */
    private function getManager(int $id): ?ManagerInterface
    {
        try {
            $manager = $id ? $this->managerRepository->getById($id) : $this->managerFactory->create();;
        } catch (NoSuchEntityException $e) {
            $manager = null;
        }
        return $manager;
    }

    /**
     * @param ManagerInterface $manager
     * @param string $eventName
     */
    private function dispatchEvent(ManagerInterface $manager, string $eventName): void
    {
        $this->_eventManager->dispatch(
            $eventName,
            [
                ConstantInterface::MANAGER_OBSERVER_PARAM => $manager,
                ConstantInterface::REQUEST_OBSERVER_PARAM => $this->getRequest()
            ]
        );
    }

    /**
     * Process result redirect
     *
     * @param ManagerInterface $manager
     * @param Redirect $resultRedirect
     * @return Redirect
     */
    private function processResultRedirect(ManagerInterface $manager, Redirect $resultRedirect): Redirect
    {
        $this->dataPersistor->clear(ConstantInterface::BACKEND_REGISTRY_MANAGER);

        if ($this->getRequest()->getParam(self::REQUEST_PARAM_BACK)) {
            return $resultRedirect->setPath('*/*/edit', ['id' => $manager->getId(), '_current' => true]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
