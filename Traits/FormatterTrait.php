<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Traits;

/**
 * Trait FormatterTrait
 * Use the trait to format some value to another format
 * @package BelSmol\VideoCall\Traits
 */
trait FormatterTrait
{
    /**
     * @param string $stringDate
     * @param string $format
     * @return string
     */
    public function formatDateFromString(string $stringDate, string $format = "m.d.y"): string
    {
        return date($format, strtotime($stringDate));
    }

    /**
     * @param int $durationSec
     * @param string $format
     * @return string
     */
    public function formatDuration(int $durationSec, string $format = "H:i:s"): string
    {
        return gmdate($format, $durationSec);
    }
}
