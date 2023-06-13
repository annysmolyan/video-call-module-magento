<?php
/**
 * Copyright (c) 2023 by https://github.com/annysmolyan
 *
 * This module provides video call functionality for an e-commerce store.
 * For license details, please view the GNU General Public License v3 (GPL 3.0)
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace BelSmol\VideoCall\Model\SignalingServer;

use BelSmol\VideoCall\Api\Data\SignalingServerMsgInterface;

/**
 * Class ServerMsg
 * @package BelSmol\VideoCall\Model
 */
class ServerMsg implements SignalingServerMsgInterface
{
    private string $message = "";
    private string $room = "";
    private array $data = [];

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $room
     */
    public function setRoom(string $room): void
    {
        $this->room = $room;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function addData(array $data): void
    {
        $this->data[] = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::MESSAGE_KEY => $this->message,
            self::ROOM_KEY => $this->room,
            self::DATA_KEY => $this->data,
        ];
    }

    /**
     * Don't use magento serializer here.
     * Php json_encode is used for relief server initialization
     *
     * @return string
     */
    public function toJson(): string
    {
        $data = $this->toArray();
        return json_encode($data);
    }
}
