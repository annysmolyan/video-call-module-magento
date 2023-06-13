<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\ManagerAuth;

use BelSmol\VideoCall\Api\AuthSessionInterface;
use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionStartChecker;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class Session
 *
 * @method ManagerInterface|null getManager()
 * @method Session setManager(ManagerInterface $value)
 *
 * @package BelSmol\VideoCall\Model\ManagerAuth
 */
class Session extends SessionManager implements AuthSessionInterface
{
    private ConfigHelper $configHelper;

    /**
     * @param Http $request
     * @param SidResolverInterface $sidResolver
     * @param ConfigInterface $sessionConfig
     * @param SaveHandlerInterface $saveHandler
     * @param ValidatorInterface $validator
     * @param StorageInterface $storage
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param State $appState
     * @param ConfigHelper $configHelper
     * @param SessionStartChecker|null $sessionStartChecker
     * @throws SessionException
     */
    public function __construct(
        Http $request,
        SidResolverInterface $sidResolver,
        ConfigInterface $sessionConfig,
        SaveHandlerInterface $saveHandler,
        ValidatorInterface $validator,
        StorageInterface $storage,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        State $appState,
        ConfigHelper $configHelper,
        ?SessionStartChecker $sessionStartChecker = null
    ) {
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState,
            $sessionStartChecker
        );

        $this->configHelper = $configHelper;
    }

    /**
     * Process of configuring of current auth storage when login was performed
     *
     * @return void
     */
    public function processLogin(): void
    {
        if ($this->getManager()) {
            $this->regenerateId();
        }
    }

    /**
     * Perform logout specific actions
     * Process of configuring of current auth storage when logout was performed
     *
     * @return void
     */
    public function processLogout(): void
    {
        $this->destroy();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->getManager() && $this->getManager()->getId();
    }

    /**
     * Prolong storage lifetime
     *
     * @return void
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function prolong(): void
    {
        if (null === $this->getManager()) {
            $this->destroy();
            return;
        }

        $lifetime = $this->configHelper->getManagerSessionLifetime();
        $cookieValue = $this->cookieManager->getCookie($this->getName());

        if ($cookieValue) {
            $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
                ->setDuration($lifetime)
                ->setPath($this->sessionConfig->getCookiePath())
                ->setDomain($this->sessionConfig->getCookieDomain())
                ->setSecure($this->sessionConfig->getCookieSecure())
                ->setHttpOnly($this->sessionConfig->getCookieHttpOnly());
            $this->cookieManager->setPublicCookie($this->getName(), $cookieValue, $cookieMetadata);
        }
    }

    /**
     * @OVERRIDE
     * Skip path validation in manager area
     *
     * @param string $path
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @codeCoverageIgnore
     */
    public function isValidForPath($path): bool
    {
        return true;
    }
}
