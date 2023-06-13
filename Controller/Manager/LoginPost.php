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
use BelSmol\VideoCall\Api\MessageInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class LoginPost
 * WARNING! The class has plugin ManagerAccountControllerPlugin.
 * The plugin checks if a manager should be redirected to another page
 * in case if the module disabled or a manager was not logged in
 *
 * @package BelSmol\VideoCall\Controller\Manager
 */
class LoginPost implements HttpPostActionInterface, ManagerAccountControllerInterface
{
    protected const POST_PARAM_LOGIN_DATA = "login";
    protected const POST_PARAM_LOGIN_DATA_LOGIN = "login";
    protected const POST_PARAM_LOGIN_DATA_PASSWORD = "password";

    private Validator $formKeyValidator;
    private AuthHandlerInterface $authHandler;
    private ManagerInterface $messageManager;
    private RequestInterface $request;
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param Context $context
     * @param Validator $formKeyValidator
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(
        Context $context,
        Validator $formKeyValidator,
        AuthHandlerInterface $authHandler
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->authHandler = $authHandler;
        $this->messageManager = $context->getMessageManager();
        $this->request = $context->getRequest();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * Process manager login
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if ($this->authHandler->isLoggedIn()) {
            return $this->redirectToPage("videocall/manager/dashboard");
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__(MessageInterface::MSG_INVALID_FORM_KEY));
            return $this->redirectToPage("videocall/manager/login");
        }

        $loginData = $this->request->getPost(self::POST_PARAM_LOGIN_DATA);
        $login = (string)$loginData[self::POST_PARAM_LOGIN_DATA_LOGIN] ?? '';
        $password = (string)$loginData[self::POST_PARAM_LOGIN_DATA_PASSWORD] ?? '';
        $this->request->setPostValue(self::POST_PARAM_LOGIN_DATA, null);

        try {
            $this->authHandler->login($login, $password);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return $this->redirectToPage("videocall/manager/login");
        }

        return $this->redirectToPage("videocall/manager/dashboard");
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
