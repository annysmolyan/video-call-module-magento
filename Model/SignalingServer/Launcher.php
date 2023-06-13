<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\SignalingServer;

use BelSmol\VideoCall\Api\ProcessServiceInterface;
use BelSmol\VideoCall\Api\SignalingServerLauncherInterface;
use BelSmol\VideoCall\Helper\ConfigHelper;
use Exception;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

/**
 * Class Launcher
 * @package BelSmol\VideoCall\Model\SignalingServer
 */
class Launcher implements SignalingServerLauncherInterface
{
    private ProcessServiceInterface $processService;
    private ConfigHelper $configHelper;

    /**
     * @param ProcessServiceInterface $processService
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        ProcessServiceInterface $processService,
        ConfigHelper $configHelper
    ) {
        $this->processService = $processService;
        $this->configHelper = $configHelper;
    }

    /**
     * Run signaling server using phpRatchet
     * @throws Exception
     */
    public function launch(): void
    {
        if ($this->processService->isProcessRunning(self::COMMAND_PROCESS_NAME)) {
            throw new Exception("Signaling has been already run");
        }

        $pid = getmypid();
        $this->processService->setProcessName($pid, self::COMMAND_PROCESS_NAME);

        $port = $this->configHelper->getSignalingServerPort();

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Server()
                )
            ),
            $port
        );

        $server->run();
    }
}
