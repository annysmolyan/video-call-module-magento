<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model;

use BelSmol\VideoCall\Api\Data\EmailTemplateInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;

/**
 * Class EmailTemplateRepository
 * @package BelSmol\VideoCall\Model
 */
class EmailTemplate implements EmailTemplateInterface
{
    protected string $from = "";
    protected string $to = "";
    protected string $templateId = "";
    protected string $fromName = "";
    protected array $templateVars = [];
    protected string $area = Area::AREA_ADMINHTML;
    protected int $storeId = Store::DEFAULT_STORE_ID;

    /**
     * @param string $from
     */
    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $id
     */
    public function setTemplateId(string $id): void
    {
        $this->templateId = $id;
    }

    /**
     * @return string
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    /**
     * @param string $name
     */
    public function setFromName(string $name): void
    {
        $this->fromName = $name;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param array $vars
     * @return mixed
     */
    public function setTemplateVars(array $vars = []): void
    {
        $this->templateVars = $vars;
    }

    /**
     * @return array
     */
    public function getTemplateVars(): array
    {
        return $this->templateVars;
    }

    /**
     * @param string $area
     */
    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    /**
     * @return string
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * @param int $id
     */
    public function setStoreId(int $id): void
    {
        $this->storeId = $id;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->storeId;
    }
}
