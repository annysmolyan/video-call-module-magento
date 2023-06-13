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
 * Interface ProcessServiceInterface
 * @package BelSmol\VideoCall\Api
 */
interface ProcessServiceInterface
{
    /**
     * @param int $pid
     * @param string $name
     */
    public function setProcessName(int $pid, string $name): void;

    /**
     * @param string $name
     * @return array
     */
    public function getActiveProcessByName(string $name): array;

    /**
     * @param string $name
     * @return bool
     */
    public function isProcessRunning(string $name): bool;

    /**
     * @param string $processName
     */
    public function stopProcessByName(string $processName): void;
}
