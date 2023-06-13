<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Console\Command;

use BelSmol\VideoCall\Api\ManagerOperatorInterface;
use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ManagerPasswordChange
 * @package BelSmol\VideoCall\Console\Command
 */
class ManagerPasswordChange extends Command
{
    private const COMMAND_NAME = "belsmol-video-call:manager:change-pass";
    private const COMMAND_DESCRIPTION = "Change manager password. Use --email= and --password options";

    private const MSG_SUCCESS = "Password has been changed";

    private const OPTION_EMAIL = "email";
    private const OPTION_EMAIL_DESCRIPTION = "Manager Email";
    private const OPTION_PASSWORD = "password";
    private const OPTION_PASSWORD_DESCRIPTION = "Password to save";

    private AppState $appState;
    private ManagerOperatorInterface $managerOperator;

    /**
     * @param ManagerOperatorInterface $managerOperator
     * @param AppState $appState
     * @param string|null $name
     */
    public function __construct(
        ManagerOperatorInterface $managerOperator,
        AppState $appState,
        string $name = null
    ) {
        parent::__construct($name);
        $this->appState = $appState;
        $this->managerOperator = $managerOperator;
    }

    /**
     * Set preferences for command
     * @return void
     */
    protected function configure(): void
    {
        $options = [
            new InputOption(self::OPTION_EMAIL,
                null,
                InputOption::VALUE_REQUIRED,
                self::OPTION_EMAIL_DESCRIPTION),
            new InputOption(self::OPTION_PASSWORD,
                null,
                InputOption::VALUE_REQUIRED,
                self::OPTION_PASSWORD_DESCRIPTION),
        ];

        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->setDefinition($options);
        parent::configure();
    }

    /**
     * Change manager password
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);

        $email = $input->getOption(self::OPTION_EMAIL);
        $password = $input->getOption(self::OPTION_PASSWORD);

        if (!$email) {
            throw new Exception("Required option " . self::OPTION_EMAIL . " required");
        }

        if (!$password) {
            throw new Exception("Required option " . self::OPTION_PASSWORD . " required");
        }

        $this->managerOperator->setManagerPasswordByEmail($email, $password);
        $output->writeln(self::MSG_SUCCESS);
    }
}
