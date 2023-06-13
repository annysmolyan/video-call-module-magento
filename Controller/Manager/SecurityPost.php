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
use BelSmol\VideoCall\Api\ManagerOperatorInterface;
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
 * Class SecurityPost
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class SecurityPost implements HttpPostActionInterface, ManagerAccountControllerInterface
{
    protected const POST_PARAM_PASSWORD_CURRENT = "currentPassword";
    protected const POST_PARAM_PASSWORD_CONFIRMED = "passwordConfirm";
    protected const POST_PARAM_PASSWORD_NEW = "newPassword";

    private FormKeyValidator $formKeyValidator;
    private ManagerInterface $messageManager;
    private RequestInterface $request;
    private AuthHandlerInterface $authHandler;
    private ManagerOperatorInterface $managerOperator;
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param Context $context
     * @param FormKeyValidator $formKeyValidator
     * @param AuthHandlerInterface $authHandler
     * @param ManagerOperatorInterface $managerOperator
     */
    public function __construct(
        Context $context,
        FormKeyValidator $formKeyValidator,
        AuthHandlerInterface $authHandler,
        ManagerOperatorInterface $managerOperator
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->messageManager = $context->getMessageManager();
        $this->request = $context->getRequest();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->authHandler = $authHandler;
        $this->managerOperator = $managerOperator;
    }

    /**
     * Save manager password
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(MessageInterface::MSG_INVALID_FORM_KEY);
            return $this->redirectToPage("videocall/manager/settings");
        }

        $manager = $this->authHandler->getUser();
        $currentPassword = $this->request->getPost(self::POST_PARAM_PASSWORD_CURRENT, "");
        $newPassword = $this->request->getPost(self::POST_PARAM_PASSWORD_NEW, "");
        $confirmedPassword = $this->request->getPost(self::POST_PARAM_PASSWORD_CONFIRMED, "");

        try {
            $this->managerOperator->updateManagerPassword($manager, $currentPassword, $newPassword, $confirmedPassword);
            $this->messageManager->addSuccessMessage(MessageInterface::MSG_PASSWORD_UPDATED);
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
