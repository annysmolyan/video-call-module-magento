<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Console\Command;

use BelSmol\VideoCall\Api\SignalingServerLauncherInterface;
use Exception;
use Magento\Framework\App\State as AppState;
use Magento\Framework\App\Area;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SignalingServerStart
 * @package BelSmol\VideoCall\Console\Command
 */
class SignalingServerStart extends Command
{
    private const COMMAND_NAME = "belsmol-video-call:signaling:start";
    private const COMMAND_DESCRIPTION = "Start signaling server. WARNING! Use the command in test purpose. For background process please use magento cron.";

    private AppState $appState;
    private SignalingServerLauncherInterface $signalingServerLauncher;

    /**
     * @param AppState $appState
     * @param SignalingServerLauncherInterface $signalingServerLauncher
     * @param string|null $name
     */
    public function __construct(
        AppState $appState,
        SignalingServerLauncherInterface $signalingServerLauncher,
        string $name = null
    ) {
        parent::__construct($name);
        $this->appState = $appState;
        $this->signalingServerLauncher = $signalingServerLauncher;
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
     * WARNING! Use this command in terminal only in test purpose.
     * For background process please use magento cron.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        try {
            $this->appState->setAreaCode(Area::AREA_ADMINHTML);
            $this->signalingServerLauncher->launch();
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }
}
