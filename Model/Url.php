<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model;

use BelSmol\VideoCall\Api\UrlInterface;
use Magento\Framework\UrlInterface as MagentoUrlInterface;

/**
 * Class Url
 * @package BelSmol\VideoCall\Model
 */
class Url implements UrlInterface
{
    protected const MANAGER_AUTH_URL = "videocall/manager/loginPost";
    protected const MANAGER_LOGIN_URL = "videocall/manager/login";
    protected const MANAGER_LOGOUT_URL = "videocall/manager/logout";
    protected const MANAGER_DASHBOARD_URL = "videocall/manager/dashboard";
    protected const MANAGER_SECURITY_POST_URL = "videocall/manager/securityPost";
    protected const MANAGER_PROFILE_POST_URL = "videocall/manager/profilePost";
    protected const CUSTOMER_CHECK_MEDIA_URL = "videocall/customer/checkmedia";
    protected const CUSTOMER_VIDEO_CALL_URL = "videocall/video/call";

    protected MagentoUrlInterface $urlBuilder;

    /**
     * @param MagentoUrlInterface $urlBuilder
     */
    public function __construct(MagentoUrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function getManagerAuthUrl(): string
    {
        return $this->buildUrl(self::MANAGER_AUTH_URL);
    }

    /**
     * @return string
     */
    public function getManagerLoginUrl(): string
    {
        return $this->buildUrl(self::MANAGER_LOGIN_URL);
    }

    /**
     * @param array $params
     * @return string
     */
    public function getCustomerVideoCallUrl(array $params = []): string
    {
        return $this->buildUrl(self::CUSTOMER_VIDEO_CALL_URL, $params);
    }

    /**
     * @return string
     */
    public function getCustomerCheckMediaUrl(): string
    {
        return $this->buildUrl(self::CUSTOMER_CHECK_MEDIA_URL);
    }

    /**
     * @return string
     */
    public function getManagerLogoutUrl(): string
    {
        return $this->buildUrl(self::MANAGER_LOGOUT_URL);
    }

    /**
     * @return string
     */
    public function getManagerDashboardUrl(): string
    {
        return $this->buildUrl(self::MANAGER_DASHBOARD_URL);
    }

    /**
     * @return string
     */
    public function getManagerSecurityPostUrl(): string
    {
        return $this->buildUrl(self::MANAGER_SECURITY_POST_URL);
    }

    /**
     * @return string
     */
    public function getManagerProfilePostUrl(): string
    {
        return $this->buildUrl(self::MANAGER_PROFILE_POST_URL);
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    protected function buildUrl(string $path, array $params = []): string
    {
        $url = $this->urlBuilder->getBaseUrl() . $path;

        if ($params) {
            $url .= "?" . http_build_query($params);
        }

        return $url;
    }
}
