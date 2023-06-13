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
 * Class ManagerInterface
 * @package BelSmol\VideoCall\Api\Data
 */
interface ManagerInterface
{
    const TABLE_NAME = "belsmol_videocall_manager";
    const WEBSITE_RELATION_TABLE = "belsmol_videocall_manager_website";

    const ENTITY_ID = "entity_id";
    const FIRST_NAME = "firstname";
    const LOGIN = "login";
    const MIDDLE_NAME = "middlename";
    const LAST_NAME = "lastname";
    const EMAIL = "email";
    const PASSWORD_HASH = "password_hash";
    const TOKEN = "token";
    const IS_ACTIVE = "is_active";
    const WEBSITES = "websites";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    //used for relations with website
    const WEBSITE_MANAGER_ID = "manager_id";
    const WEBSITE_WEBSITE_ID = "website_id";

    /**
     * Dont use return type here, because of conflict with \Magento\Framework\Model\AbstractModel
     * @return int|null
     */
    public function getId();

    /**
     * Dont use return type and type hint here, because of conflict with \Magento\Framework\Model\AbstractModel
     * @param $value
     * @return ManagerInterface
     */
    public function setId($value);

    /**
     * @param string $login
     * @return ManagerInterface
     */
    public function setLogin(string $login): ManagerInterface;

    /**
     * @return string
     */
    public function getLogin(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @param string $firstName
     * @return ManagerInterface
     */
    public function setFirstName(string $firstName): self;

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string;

    /**
     * @param string|null $middleName
     * @return ManagerInterface
     */
    public function setMiddleName(?string $middleName): self;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @param string|null $lastName
     * @return ManagerInterface
     */
    public function setLastName(?string $lastName): self;

    /**
     * @return string|null
     */
    public function getEmail(): string;

    /**
     * @param string|null $email
     * @return ManagerInterface
     */
    public function setEmail(string $email): self;

    /**
     * @return string
     */
    public function getPasswordHash(): string;

    /**
     * @param string|null $hash
     * @return ManagerInterface
     */
    public function setPasswordHash(?string $hash): self;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return ManagerInterface
     */
    public function setIsActive(bool $isActive): self;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $timeStamp
     * @return ManagerInterface
     */
    public function setCreatedAt(string $timeStamp): self;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $timeStamp
     * @return ManagerInterface
     */
    public function setUpdatedAt(string $timeStamp): self;

    /**
     * @param array $websites
     * @return ManagerInterface
     */
    public function setWebsites(array $websites): self;

    /**
     * @return array
     */
    public function getWebsites(): array;

    /**
     * @return string
     */
    public function getFullName(): string;

    /**
     * @param string $token
     */
    public function setToken(string $token): void;

    /**
     * @return string
     */
    public function getToken(): string;
}
