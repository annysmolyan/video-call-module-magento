<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Block\Adminhtml\System\Config\Form;

use BelSmol\VideoCall\Api\ConstantInterface;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class ServerAddresses
 * Dynamic row for stun/turn server config
 * @package BelSmol\VideoCall\Block\Adminhtml\System\Config\Form
 */
class ServerAddresses extends AbstractFieldArray
{
    const LABEL_KEY = 'label';
    const CLASS_KEY = 'class';

    const LABEL_SERVER = "Server URL With Port";
    const LABEL_CREDENTIAL = "Credential";
    const LABEL_USERNAME = "Username";
    const LABEL_ADD_BTN = "Add";

    /**
     * Prepare rendering the new field by adding all the needed columns
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(ConstantInterface::CONFIG_ROW_SERVER_URL_ADDRESS, [
            self::LABEL_KEY => __(self::LABEL_SERVER),
            self::CLASS_KEY => 'required-entry'
        ]);

        $this->addColumn(ConstantInterface::CONFIG_ROW_SERVER_CREDENTIAL, [
            self::LABEL_KEY => __(self::LABEL_CREDENTIAL),
        ]);

        $this->addColumn(ConstantInterface::CONFIG_ROW_SERVER_USERNAME, [
            self::LABEL_KEY => __(self::LABEL_USERNAME),
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __(self::LABEL_ADD_BTN);
    }
}
