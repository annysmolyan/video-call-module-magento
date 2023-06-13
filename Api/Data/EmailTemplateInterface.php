<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api\Data;

/**
 * Interface EmailTemplateInterface
 * @package BelSmol\VideoCall\Api\Data
 */
interface EmailTemplateInterface
{
    const MANAGER_ACCOUNT_EMAIL_TEMPLATE_ID = "belsmol_videocall_manager_account_created_template";

    /**
     * @param string $from
     */
    public function setFrom(string $from): void;

    /**
     * @return string
     */
    public function getFrom(): string;

    /**
     * @param string $to
     */
    public function setTo(string $to): void;

    /**
     * @return string
     */
    public function getTo(): string;

    /**
     * @param string $id
     */
    public function setTemplateId(string $id): void;

    /**
     * @return string
     */
    public function getTemplateId(): string;

    /**
     * @param string $name
     */
    public function setFromName(string $name): void;

    /**
     * @return string
     */
    public function getFromName(): string;

    /**
     * @param array $vars
     * @return mixed
     */
    public function setTemplateVars(array $vars = []): void;

    /**
     * @return array
     */
    public function getTemplateVars(): array;

    /**
     * @param string $area
     */
    public function setArea(string $area): void;

    /**
     * @return string
     */
    public function getArea(): string;

    /**
     * @param int $id
     */
    public function setStoreId(int $id): void;

    /**
     * @return int
     */
    public function getStoreId(): int;
}
