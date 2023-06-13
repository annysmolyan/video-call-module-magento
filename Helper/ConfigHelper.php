<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigHelper
 * @package BelSmol\VideoCall\Helper
 */
class ConfigHelper extends AbstractHelper
{
    const CONFIG_ENABLED = 'videocall/general/enabled';
    const CONFIG_SESSION_LIFETIME = 'videocall/security/session_lifetime';
    const CONFIG_EMAIL_FROM = "videocall/email/email_from";
    const CONFIG_NAME_FROM = "videocall/email/name_from";
    const CONFIG_WEBRTC_ADAPTER = "videocall/webrtc/adapter";
    const CONFIG_WEBRTC_SIGNALING_SERVER_HOST = "videocall/webrtc/signaling_server_host";
    const CONFIG_WEBRTC_SIGNALING_SERVER_PORT = "videocall/webrtc/signaling_server_port";
    const CONFIG_WEBRTC_CHECK_SIGNALING_BY_CRON = "videocall/webrtc/check_by_cron";
    const CONFIG_ICE_SERVERS = "videocall/server/ice_servers";
    const CONFIG_SUPPORT_EMAIL = "videocall/customer_account/support_email";

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getManagerSessionLifetime(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::CONFIG_SESSION_LIFETIME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return (string)$this->scopeConfig->getValue(self::CONFIG_EMAIL_FROM);
    }

    /**
     * @return string
     */
    public function getNameFrom(): string
    {
        return (string)$this->scopeConfig->getValue(self::CONFIG_NAME_FROM);
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_SUPPORT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getWebrtcAdapter(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_WEBRTC_ADAPTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSignalingServerHost(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_WEBRTC_SIGNALING_SERVER_HOST,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getSignalingServerPort(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::CONFIG_WEBRTC_SIGNALING_SERVER_PORT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isCheckSignalingByCron(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_WEBRTC_CHECK_SIGNALING_BY_CRON,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getIceServers(): array
    {
        $urls = [];
        $iceServers = $this->scopeConfig->getValue(
            self::CONFIG_ICE_SERVERS,
            ScopeInterface::SCOPE_STORE
        );

        if (!$iceServers) {
            return $urls;
        }

        $decodedConfig = json_decode($iceServers);

        foreach ($decodedConfig as $uid => $serverConfig) {
            if ($serverConfig->credential && $serverConfig->username) {
                $urls[] = [
                    "urls" => $serverConfig->url,
                    "credential" => $serverConfig->credential,
                    "username" => $serverConfig->username,
                ];
            } else {
                $urls[] = [
                    "urls" => $serverConfig->url,
                ];
            }
        }

        return $urls;
    }
}
