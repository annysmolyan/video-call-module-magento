<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Block\Manager\Account;

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Framework\View\Element\Html\Links;

/**
 * Class Navigation
 * Class for sorting links in navigation panels.
 *
 * @package BelSmol\VideoCall\Block\Manager\Account
 */
class Navigation extends Links
{
    /**
     * @OVERRIDE
     * added sorting to links
     *
     * @return array
     */
    public function getLinks(): array
    {
        $links = parent::getLinks();
        $sortableLink = [];
        foreach ($links as $key => $link) {
            if ($link instanceof SortLinkInterface) {
                $sortableLink[] = $link;
                unset($links[$key]);
            }
        }

        usort($sortableLink, [$this, "compare"]);
        return array_merge($sortableLink, $links);
    }

    /**
     * Compare sortOrder in links.
     *
     * @param SortLinkInterface $firstLink
     * @param SortLinkInterface $secondLink
     * @return int
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function compare(SortLinkInterface $firstLink, SortLinkInterface $secondLink): int
    {
        return $secondLink->getSortOrder() <=> $firstLink->getSortOrder();
    }
}
