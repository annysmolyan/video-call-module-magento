<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Plugin\Controller;

use BelSmol\VideoCall\Api\AuthSessionInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\ManagerAccountControllerInterface as Subject;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Closure;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class ManagerAccountControllerPlugin
 * Plugin for Manager Account controllers.
 *
 * @package BelSmol\VideoCall\Plugin\Controller
 */
class ManagerAccountControllerPlugin
{
    private ConfigHelper $configHelper;
    private RequestInterface $request;
    private array $allowedActions;
    private RedirectFactory $resultRedirectFactory;
    private AuthSessionInterface $authSession;
    private ManagerRepositoryInterface $managerRepository;

    /**
     * @param ConfigHelper $configHelper
     * @param AuthSessionInterface $authSession
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     * @param ManagerRepositoryInterface $managerRepository
     * @param array $allowedActions
     */
    public function __construct(
        ConfigHelper $configHelper,
        AuthSessionInterface $authSession,
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        ManagerRepositoryInterface $managerRepository,
        array $allowedActions = []
    ) {
        $this->configHelper = $configHelper;
        $this->authSession = $authSession;
        $this->request = $request;
        $this->allowedActions = $allowedActions;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->managerRepository = $managerRepository;
    }

    /**
     * Executes original method if allowed, otherwise - redirects to log in
     * Verifies permissions using Action Name against injected (`di.xml`) rules
     *
     * @param Subject $controllerAction
     * @param Closure $proceed
     * @return ResultInterface
     */
    public function aroundExecute(Subject $controllerAction, Closure $proceed): ResultInterface
    {
        if (!$this->configHelper->isModuleEnabled()) {
            return $this->redirectToPage("noroute");
        }

        $isManagerLoggedIn = $this->isManagerLoggedIn();

        if ($isManagerLoggedIn) {
            $this->authSession->prolong();
        } else if ($this->request->getActionName() != "loginPost") {
            $this->authSession->processLogout();
        }

        if ($this->isActionAllowed() || $isManagerLoggedIn) {
            return $proceed();
        }

        return $this->redirectToPage("videocall/manager/login");
    }

    /**
     * Check that logged in manager account was not removed
     * and he can be logged in
     * @return bool
     */
    private function isManagerLoggedIn(): bool
    {
        $manager = $this->getManagerFromDB();
        return $manager && $this->authSession->isLoggedIn() && $manager->getIsActive();
    }

    /**
     * @return ManagerInterface|null
     */
    private function getManagerFromDB(): ?ManagerInterface
    {
        $manager = null;
        $loggedInManager = $this->authSession->getManager();

        if ($loggedInManager) {
            try {
                $manager = $this->managerRepository->getById($loggedInManager->getId());
            } catch (\Exception $exception) {
                $manager = null;
            }
        }

        return $manager;
    }

    /**
     * Check that action is available without login
     * @return bool
     */
    private function isActionAllowed(): bool
    {
        $action = strtolower($this->request->getActionName());
        return in_array($action, $this->allowedActions);
    }

    /**
     * @param string $path
     * @return ResultInterface
     */
    private function redirectToPage(string $path): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($path);
        return $resultRedirect;
    }
}
