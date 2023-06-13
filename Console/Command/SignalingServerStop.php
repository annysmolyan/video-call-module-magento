<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Console\Command;

use BelSmol\VideoCall\Api\ProcessServiceInterface;
use BelSmol\VideoCall\Api\SignalingServerLauncherInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SignalingServerStop
 * @package BelSmol\VideoCall\Console\Command
 */
class SignalingServerStop extends Command
{
    private const COMMAND_NAME = "belsmol-video-call:signaling:stop";
    private const COMMAND_DESCRIPTION = "Stop signaling server";

    private const MSG_SERVER_STOPPED = "Signaling server stopped";
    private const MSG_SERVER_NOT_RUN = "Signaling server was not run";

    private AppState $appState;
    private ProcessServiceInterface $processService;

    /**
     * @param AppState $appState
     * @param ProcessServiceInterface $processService
     * @param string|null $name
     */
    public function __construct(
        AppState $appState,
        ProcessServiceInterface $processService,
        string $name = null
    ) {
        parent::__construct($name);
        $this->appState = $appState;
        $this->processService = $processService;
    }

    /**
     * Set preferences for command
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        parent::configure();
    }

    /**
     * Start signaling server. This class depends on phpRatchet lib
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);

        if ($this->processService->isProcessRunning(SignalingServerLauncherInterface::COMMAND_PROCESS_NAME)) {
            $this->processService->stopProcessByName(SignalingServerLauncherInterface::COMMAND_PROCESS_NAME);
            $output->writeln(self::MSG_SERVER_STOPPED);
        } else {
            $output->writeln(self::MSG_SERVER_NOT_RUN);
        }
    }
}
