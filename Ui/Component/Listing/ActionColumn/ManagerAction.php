<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\ActionColumn;

use BelSmol\VideoCall\Api\Data\ManagerInterface;

/**
 * Class ManagerAction
 * @package BelSmol\VideoCall\Ui\Component\Listing\ActionColumn
 */
class ManagerAction extends AbstractAction
{
    const ROW_EDIT_URL = 'belsmol_videocall/manager/edit';

    /**
     * Return edit path
     * @return string
     */
    public function getEditUrlPath(): string
    {
        return self::ROW_EDIT_URL;
    }

    /**
     * Return edit path
     * @return string
     */
    public function getEntityIdFieldName(): string
    {
        return ManagerInterface::ENTITY_ID;
    }
}
