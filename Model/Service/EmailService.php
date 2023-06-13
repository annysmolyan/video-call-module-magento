<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Service;

use BelSmol\VideoCall\Api\Data\EmailTemplateInterface;
use BelSmol\VideoCall\Api\EmailServiceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * Class EmailService
 * @package BelSmol\VideoCall\Model\Service
 */
class EmailService implements EmailServiceInterface
{
    protected const TEMPLATE_OPTION_AREA = "area";
    protected const TEMPLATE_OPTION_STORE = "store";
    protected const FROM_EMAIL = "email";
    protected const FROM_NAME = "name";

    protected TransportBuilder $transportBuilder;

    /**
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(TransportBuilder $transportBuilder)
    {
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @param EmailTemplateInterface $emailTemplate
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendEmail(EmailTemplateInterface $emailTemplate): void
    {
        $transport = $this->transportBuilder
            ->setTemplateOptions([
                self::TEMPLATE_OPTION_AREA => $emailTemplate->getArea(),
                self::TEMPLATE_OPTION_STORE => $emailTemplate->getStoreId()
            ])
            ->setFromByScope([
                self::FROM_EMAIL => $emailTemplate->getFrom(),
                self::FROM_NAME => $emailTemplate->getFromName()
            ])
            ->setTemplateIdentifier($emailTemplate->getTemplateId())
            ->setTemplateVars($emailTemplate->getTemplateVars())
            ->addTo($emailTemplate->getTo())
            ->getTransport();

        $transport->sendMessage();
    }
}
