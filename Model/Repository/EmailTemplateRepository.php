<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Repository;

use BelSmol\VideoCall\Api\Data\EmailTemplateInterface;
use BelSmol\VideoCall\Api\Data\EmailTemplateInterfaceFactory;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Api\EmailTemplateRepositoryInterface;
use BelSmol\VideoCall\Api\UrlInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;

/**
 * Class EmailTemplateRepository
 * @package BelSmol\VideoCall\Model\Repository
 */
class EmailTemplateRepository implements EmailTemplateRepositoryInterface
{
    protected ConfigHelper $configHelper;
    protected EmailTemplateInterfaceFactory $emailTemplateFactory;
    protected UrlInterface $urlModel;

    /**
     * @param ConfigHelper $configHelper
     * @param EmailTemplateInterfaceFactory $emailTemplateFactory
     * @param UrlInterface $urlModel
     */
    public function __construct(
        ConfigHelper $configHelper,
        EmailTemplateInterfaceFactory $emailTemplateFactory,
        UrlInterface $urlModel
    ) {
        $this->configHelper = $configHelper;
        $this->emailTemplateFactory = $emailTemplateFactory;
        $this->urlModel = $urlModel;
    }

    /**
     * @param ManagerInterface $manager
     * @param string $password
     * @return EmailTemplateInterface
     */
    public function getManagerPasswordEmailTemplate(
        ManagerInterface $manager,
        string $password
    ): EmailTemplateInterface {
        $emailTemplate = $this->emailTemplateFactory->create();
        $emailTemplate->setFrom($this->configHelper->getEmailFrom());
        $emailTemplate->setFromName($this->configHelper->getNameFrom());
        $emailTemplate->setTemplateId(EmailTemplateInterface::MANAGER_ACCOUNT_EMAIL_TEMPLATE_ID);
        $emailTemplate->setTo($manager->getEmail());
        $emailTemplate->setTemplateVars([
            "login" => $manager->getLogin(),
            "password" => $password,
            "loginUrl" => $this->urlModel->getManagerLoginUrl(),
        ]);

        return $emailTemplate;
    }
}
