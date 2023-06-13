<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Ui\Component\Listing\Column\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\System\Store as SystemStore;

/**
 * Class Websites
 * @package BelSmol\VideoCall\Ui\Component\Listing\Column\Options
 */
class Websites implements OptionSourceInterface
{
    protected const VALUE_KEY = "value";
    protected const LABEL_KEY = "label";

    private SystemStore $systemStore;

    /**
     * @param SystemStore $systemStore
     */
    public function __construct(SystemStore $systemStore)
    {
        $this->systemStore = $systemStore;
    }

    /**
     * Get available store options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $websiteCollection = $this->systemStore->getWebsiteCollection();
        $websites = [];

        foreach ($websiteCollection as $website) {
            $websites[] = [
                self::LABEL_KEY => $website->getName(),
                self::VALUE_KEY => $website->getId(),
            ];
        }

        return array_values($websites);
    }
}
