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
 * Interface SignalingServerInterface
 * @package BelSmol\VideoCall\Api
 */
interface SignalingServerLauncherInterface
{
    const COMMAND_PROCESS_NAME = "bsSignaling";

    /**
     * @return void
     */
    public function launch(): void;
}
