<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Cron;

use BelSmol\VideoCall\Helper\ConfigHelper;
use Psr\Log\LoggerInterface;

/**
 * Class SignalingServerCron
 * @package BelSmol\VideoCall\Cron
 */
class SignalingServerCron
{
    private const MSG_START_CRON = "Signaling Server Cron Start";

    private ConfigHelper $configHelper;
    private LoggerInterface $logger;

    /**
     * @param ConfigHelper $configHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigHelper $configHelper,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->logger = $logger;
    }

    /**
     * Check that signaling server is started and run server if not.
     * Run command in background
     */
    public function execute()
    {
        $this->logger->info(self::MSG_START_CRON);

        if ($this->configHelper->isModuleEnabled() && $this->configHelper->isCheckSignalingByCron()) {
            exec("php bin/magento belsmol-video-call:signaling:start > /dev/null &");
        }
    }
}
