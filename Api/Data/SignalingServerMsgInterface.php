<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Api\Data;

/**
 * Interface SignalingServerMsgInterface
 * @package BelSmol\VideoCall\Api\Data
 */
interface SignalingServerMsgInterface
{
    const MESSAGE_KEY = "message";
    const ROOM_KEY = "room";
    const DATA_KEY = "data";

    /**
     * @param string $action
     */
    public function setMessage(string $action): void;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $room
     */
    public function setRoom(string $room): void;

    /**
     * @return string
     */
    public function getRoom(): string;

    /**
     * @param array $data
     */
    public function setData(array $data): void;

    /**
     * @param array $data
     */
    public function addData(array $data): void;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function toJson(): string;
}
