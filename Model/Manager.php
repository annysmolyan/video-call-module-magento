<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model;

use BelSmol\VideoCall\Api\Data\ManagerInterface;
use BelSmol\VideoCall\Model\ResourceModel\Manager as ManagerResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Manager
 * @package BelSmol\VideoCall\Model
 */
class Manager extends AbstractModel implements IdentityInterface, ManagerInterface
{
    const CACHE_TAG = "belsmol_videocall_manager";

    /**
     * @var string
     */
    protected $_eventPrefix = "belsmol_videocall_manager_event";

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ManagerResourceModel::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return (string)$this->getData(self::FIRST_NAME);
    }

    /**
     * @param string $firstName
     * @return ManagerInterface
     */
    public function setFirstName(string $firstName): ManagerInterface
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->getData(self::MIDDLE_NAME);
    }

    /**
     * @param string|null $middleName
     * @return ManagerInterface
     */
    public function setMiddleName(?string $middleName): ManagerInterface
    {
        return $this->setData(self::MIDDLE_NAME, $middleName);
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @param string|null $lastName
     * @return ManagerInterface
     */
    public function setLastName(?string $lastName): ManagerInterface
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @return string|null
     */
    public function getEmail(): string
    {
        return (string)$this->getData(self::EMAIL);
    }

    /**
     * @param string|null $email
     * @return ManagerInterface
     */
    public function setEmail(string $email): ManagerInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return (string)$this->getData(self::PASSWORD_HASH);
    }

    /**
     * @param string|null $hash
     * @return ManagerInterface
     */
    public function setPasswordHash(?string $hash): ManagerInterface
    {
        return $this->setData(self::PASSWORD_HASH, (string)$hash);
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * @param bool $isActive
     * @return ManagerInterface
     */
    public function setIsActive(bool $isActive): ManagerInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param string $timeStamp
     * @return ManagerInterface
     */
    public function setCreatedAt(string $timeStamp): ManagerInterface
    {
        return $this->setData(self::CREATED_AT, $timeStamp);
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param string $timeStamp
     * @return ManagerInterface
     */
    public function setUpdatedAt(string $timeStamp): ManagerInterface
    {
        return $this->setData(self::UPDATED_AT, $timeStamp);
    }

    /**
     * @param string $login
     * @return ManagerInterface
     */
    public function setLogin(string $login): ManagerInterface
    {
        return $this->setData(self::LOGIN, $login);
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return (string)$this->getData(self::LOGIN);
    }

    /**
     * @param array $websites
     * @return ManagerInterface
     */
    public function setWebsites(array $websites): ManagerInterface
    {
        return $this->setData(self::WEBSITES, $websites);
    }

    /**
     * @return array
     */
    public function getWebsites(): array
    {
        return (array)$this->getData(self::WEBSITES);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        $name = $this->getFirstName();

        if ($middleName = $this->getMiddleName()) {
            $name .= ' ' . $middleName;
        }

        if ($lastName = $this->getLastName()) {
            $name .= ' ' . $lastName;
        }
        return $name;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->setData(self::TOKEN, $token);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return (string)$this->getData(self::TOKEN);
    }
}
