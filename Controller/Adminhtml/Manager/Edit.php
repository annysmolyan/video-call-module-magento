<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Controller\Adminhtml\Manager;

use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterfaceFactory;
use BelSmol\VideoCall\Api\ManagerRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package BelSmol\VideoCall\Controller\Adminhtml\Manager
 */
class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = "BelSmol_VideoCall::managerEdit";

    protected const REQUEST_PARAM_ID = "id";
    protected const MENU_ID = "BelSmol_VideoCall::menu_manager";
    protected const PAGE_TITLE_EDIT = "Edit Manager";
    protected const PAGE_TITLE_NEW = "Create New Manager";
    protected const MSG_NOT_FOUND = "This page no longer exists.";

    protected PageFactory $resultPageFactory;
    private ManagerRepositoryInterface $managerRepository;
    private Registry $registry;
    private ManagerInterfaceFactory $managerFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ManagerRepositoryInterface $managerRepository
     * @param ManagerInterfaceFactory $managerFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ManagerRepositoryInterface $managerRepository,
        ManagerInterfaceFactory $managerFactory,
        Registry $registry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->managerRepository = $managerRepository;
        $this->registry = $registry;
        $this->managerFactory = $managerFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $managerId = (int)$this->getRequest()->getParam(self::REQUEST_PARAM_ID);

        try {
            $manager = $this->getManager($managerId);
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage(__(self::MSG_NOT_FOUND));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $this->registry->register(ConstantInterface::BACKEND_REGISTRY_MANAGER, $manager);

        return $this->initAction($manager);
    }

    /**
     * @param int|null $managerId
     * @return ManagerInterface
     */
    private function getManager(int $managerId = null): ManagerInterface
    {
        return $managerId ? $this->managerRepository->getById($managerId) : $this->managerFactory->create();
    }

    /**
     * load layout, set active menu
     * @param ManagerInterface $manager
     * @return Page
     */
    protected function initAction(ManagerInterface $manager): Page
    {
        $pageTitle = $manager->getId()
            ? __(self::PAGE_TITLE_EDIT) . ': ' . $manager->getFirstName()
            : __(self::PAGE_TITLE_NEW);

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        /** @var Page $resultPage */
        $resultPage->setActiveMenu(self::MENU_ID);
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);

        return $resultPage;
    }
}
