<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\Service;

use BelSmol\VideoCall\Api\ProcessServiceInterface;

/**
 * Class ProcessService
 * Manage processes
 * @package BelSmol\VideoCall\Model\Service
 */
class ProcessService implements ProcessServiceInterface
{
    protected const COUNT_ONE_PROCESS = 1;
    protected const PROCESS_DIR = "/proc/";
    protected const PROCESS_COMMAND_FILE = "comm";

    /**
     * @param int $pid
     * @param string $name
     */
    public function setProcessName(int $pid, string $name): void
    {
        file_put_contents(self::PROCESS_DIR . $pid . DIRECTORY_SEPARATOR . self::PROCESS_COMMAND_FILE, $name);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getActiveProcessByName(string $name): array
    {
        $pgrepList = [];
        exec("pgrep $name", $pgrepList);
        return $pgrepList;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isProcessRunning(string $name): bool
    {
        $pgrepList = $this->getActiveProcessByName($name);
        return count($pgrepList) == self::COUNT_ONE_PROCESS;
    }

    /**
     * @param string $processName
     */
    public function stopProcessByName(string $processName): void
    {
        $activeProcess = $this->getActiveProcessByName($processName);

        if ($activeProcess) {
            $processPid = $activeProcess[0];
            exec("kill -9 $processPid");
        }
    }
}
