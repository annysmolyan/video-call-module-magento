<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Manager;

use BelSmol\VideoCall\Api\AuthHandlerInterface;
use BelSmol\VideoCall\Api\ManagerAccountControllerInterface;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Api\MessageInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class ProfilePost
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class ProfilePost implements HttpPostActionInterface, ManagerAccountControllerInterface
{
    protected const POST_PARAM_FIRST_NAME = "firstname";
    protected const POST_PARAM_MIDDLE_NAME = "middlename";
    protected const POST_PARAM_LAST_NAME = "lastname";

    private RequestInterface $request;
    private RedirectFactory $resultRedirectFactory;
    private AuthHandlerInterface $authHandler;
    private FormKeyValidator $formKeyValidator;
    private ManagerInterface $messageManager;
    private ManagerRepositoryInterface $managerRepository;

    /**
     * @param Context $context
     * @param AuthHandlerInterface $authHandler
     * @param FormKeyValidator $formKeyValidator
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        Context $context,
        AuthHandlerInterface $authHandler,
        FormKeyValidator $formKeyValidator,
        ManagerRepositoryInterface $managerRepository
    ) {
        $this->request = $context->getRequest();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->authHandler = $authHandler;
        $this->formKeyValidator = $formKeyValidator;
        $this->messageManager = $context->getMessageManager();
        $this->managerRepository = $managerRepository;
    }

    /**
     * Save manager profile data
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(MessageInterface::MSG_INVALID_FORM_KEY);
            return $this->redirectToPage("videocall/manager/settings");
        }

        $manager = $this->authHandler->getUser();
        $firstName = $this->request->getPost(self::POST_PARAM_FIRST_NAME, "");
        $middleName = $this->request->getPost(self::POST_PARAM_MIDDLE_NAME, "");
        $lastName = $this->request->getPost(self::POST_PARAM_LAST_NAME, "");

        try {
            $manager->setFirstName($firstName);
            $manager->setMiddleName($middleName);
            $manager->setLastName($lastName);
            $this->managerRepository->save($manager);
            $this->messageManager->addSuccessMessage(MessageInterface::MSG_DATA_UPDATED);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->redirectToPage("videocall/manager/settings");
    }

    /**
     * @param string $path
     * @return Redirect
     */
    protected function redirectToPage(string $path): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($path);
    }
}
