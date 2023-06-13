<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\ViewModel;

use BelSmol\VideoCall\Api\UrlInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class ManagerLoginViewModel
 * @package BelSmol\VideoCall\ViewModel
 */
class ManagerLoginViewModel implements ArgumentInterface
{
    protected UrlInterface $urlModel;
    protected FormKey $formKey;

    /**
     * @param UrlInterface $urlModel
     * @param FormKey $formKey
     */
    public function __construct(
        UrlInterface $urlModel,
        FormKey $formKey
    ) {
        $this->urlModel = $urlModel;
        $this->formKey = $formKey;
    }

    /**
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->urlModel->getManagerAuthUrl();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }
}
