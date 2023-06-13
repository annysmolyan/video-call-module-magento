<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api;

/**
 * Interface UrlInterface
 * @package BelSmol\VideoCall\Api
 */
interface UrlInterface
{
    /**
     * @return string
     */
    public function getManagerAuthUrl(): string;

    /**
     * @return string
     */
    public function getManagerLoginUrl(): string;

    /**
     * @param array $params
     * @return string
     */
    public function getCustomerVideoCallUrl(array $params = []): string;

    /**
     * @return string
     */
    public function getCustomerCheckMediaUrl(): string;

    /**
     * @return string
     */
    public function getManagerLogoutUrl(): string;

    /**
     * @return string
     */
    public function getManagerDashboardUrl(): string;

    /**
     * @return string
     */
    public function getManagerSecurityPostUrl(): string;

    /**
     * @return string
     */
    public function getManagerProfilePostUrl(): string;
}
