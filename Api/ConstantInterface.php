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
 * Interface ConstantInterface
 * @package BelSmol\VideoCall\Api
 */
interface ConstantInterface
{
    const BACKEND_REGISTRY_MANAGER = "manager";

    const MANAGER_OBSERVER_PARAM = "manager";
    const REQUEST_OBSERVER_PARAM = "request";

    const CONFIG_ROW_SERVER_URL_ADDRESS = "url";
    const CONFIG_ROW_SERVER_CREDENTIAL = "credential";
    const CONFIG_ROW_SERVER_USERNAME = "username";

    const REQUEST_ROOM_ID_PARAM = "room";
}
