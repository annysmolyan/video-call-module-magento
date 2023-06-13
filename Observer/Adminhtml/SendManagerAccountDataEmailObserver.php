<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Observer\Adminhtml;

use BelSmol\VideoCall\Api\ConstantInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\EmailServiceInterface;
use BelSmol\VideoCall\Api\EmailTemplateRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ManagerCreateObserver
 * @package BelSmol\VideoCall\Observer\Adminhtml
 */
class SendManagerAccountDataEmailObserver implements ObserverInterface
{
    private EmailServiceInterface $emailService;
    private EmailTemplateRepositoryInterface $emailTemplateRepository;

    /**
     * @param EmailTemplateRepositoryInterface $emailTemplateRepository
     * @param EmailServiceInterface $emailService
     */
    public function __construct(
        EmailTemplateRepositoryInterface $emailTemplateRepository,
        EmailServiceInterface $emailService
    ) {
        $this->emailService = $emailService;
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * Send email with generated password to manager on create
     * If a password is empty then this is not a new user,
     * because the password is generated only when creating a manager
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var ManagerInterface $manager */
        $manager = $observer->getData(ConstantInterface::MANAGER_OBSERVER_PARAM);
        $password = $manager->getPassword();

        if (!$password) {
            return;
        }

        $emailTemplate = $this->emailTemplateRepository->getManagerPasswordEmailTemplate($manager, $password);
        $this->emailService->sendEmail($emailTemplate);
    }
}
