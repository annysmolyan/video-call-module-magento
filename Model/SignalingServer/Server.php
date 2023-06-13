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
use Exception;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

/**
 * Class Server
 * Signaling Server (socket client) is responsible for user connection to a call room.
 * @package BelSmol\VideoCall\Model
 */
class Server implements MessageComponentInterface
{
    private const ACTION_SUBSCRIBE = "subscribe";

    private const MSG_SUBSCRIBED = "subscribed";
    private const MSG_NEW_SUBSCRIPTION = "newSubscription";
    private const MSG_SUBSCRIBE_REJECTED = "subscribeRejected";
    private const MSG_CLOSE_CONNECTION = "closeConnection";
    private const MSG_MAX_ROOM_CONNECTION_REACHED = "maxConnectionReached";

    private const MAX_ROOM_CONNECTION = 2;

    private SplObjectStorage $clients;
    private array $rooms;

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->rooms = [];
    }

    /**
     * Store the new connection in $this->clients
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }

    /**
     * Get message from connected resources and send notifications to clients
     *
     * @param ConnectionInterface $from
     * @param string $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $decodedMsg = json_decode($msg);
        $action = $decodedMsg->action;
        $room = $decodedMsg->room ?? null;

        switch ($action) {
            case self::ACTION_SUBSCRIBE:
                $this->subscribe($room, $from);
                break;
            default:
                $data = $decodedMsg->data ?? null;
                $serverMessage = $this->createServerMessage();
                $serverMessage->setData([$data]);
                $serverMessage->setMessage($action);
                $serverMessage->setRoom($room);
                $this->notify($room, $from, $serverMessage);
                break;
        }
    }

    /**
     * If connection is closed, remove connection and notify other users about it
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        $serverMessage = $this->createServerMessage();
        $serverMessage->setMessage(self::MSG_CLOSE_CONNECTION);

        foreach ($this->rooms as $room => $connections) {
            $serverMessage->setRoom($room);
            foreach ($connections as $index => $connection) {
                if ($connection->resourceId == $conn->resourceId) { //if a disconnecting user subscribed to this room
                    unset($this->rooms[$room][$index]);
                    $this->notify($room, $conn, $serverMessage); //notify other subscribers that he has disconnected
                }
            }
        }
    }

    /**
     * Close connection if error
     *
     * @param ConnectionInterface $conn
     * @param Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        $conn->close();
    }

    /**
     * Subscribe a user to a room only if he hasn't subscribed.
     * if a room does not exist, create it and add user
     *
     * @param string $room
     * @param ConnectionInterface $from
     * @return void
     */
    private function subscribe(string $room, ConnectionInterface $from): void
    {
        $serverMessage = $this->createServerMessage();

        if ($this->isRoomMaxConnectionReached($room)) {
            $serverMessage->setMessage(self::MSG_MAX_ROOM_CONNECTION_REACHED);
            $serverMessage->setRoom($room);
        } elseif ($this->isCanSubscribeRoom($room, $from)) {
            $this->rooms[$room][] = $from;
            $serverMessage->setMessage(self::MSG_NEW_SUBSCRIPTION);
            $serverMessage->setRoom($room);
            $this->notify($room, $from, $serverMessage);
            $serverMessage->setMessage(self::MSG_SUBSCRIBED);
        } else {
            $serverMessage->setMessage(self::MSG_SUBSCRIBE_REJECTED); //a user is subscribed to another device/browser
            $serverMessage->setRoom($room);
        }

        $from->send($serverMessage->toJson());
    }

    /**
     * Notify users from room
     * @param string $room
     * @param ConnectionInterface $from
     * @param SignalingServerMsgInterface $serverMessage
     * @return void
     */
    private function notify(string $room, ConnectionInterface $from, SignalingServerMsgInterface $serverMessage): void
    {
        $broadcastMsg = $serverMessage->toJson();

        foreach ($this->rooms[$room] as $client) {
            if ($client->resourceId !== $from->resourceId) {
                $client->send($broadcastMsg);
            }
        }
    }

    /**
     * Check if a given room exist
     *
     * @param string $room
     * @return bool
     */
    private function isRoomExist(string $room): bool
    {
        return array_key_exists($room, $this->rooms);
    }

    /**
     * Check if a user has been subscribed to a room
     * @param string $room
     * @param ConnectionInterface $user
     * @return bool
     */
    private function isUserSubscribedRoom(string $room, ConnectionInterface $user): bool
    {
        return in_array($user, $this->rooms[$room]);
    }

    /**
     * Subscribe a user to a room only if he hasn't subscribed or room is not exist
     * @param string $room
     * @param ConnectionInterface $user
     * @return bool
     */
    private function isCanSubscribeRoom(string $room, ConnectionInterface $user): bool
    {
        return ($this->isRoomExist($room) && !$this->isUserSubscribedRoom($room, $user))
            || !$this->isRoomExist($room);
    }

    /**
     * Check that a room has allowed connection count
     *
     * @param string $room
     * @return bool
     */
    private function isRoomMaxConnectionReached(string $room): bool
    {
        return isset($this->rooms[$room]) && (count($this->rooms[$room]) >= self::MAX_ROOM_CONNECTION);
    }

    /**
     * Don't use object factory here.
     * Use direct object to relief server initialization
     *
     * @return SignalingServerMsgInterface
     */
    private function createServerMessage(): SignalingServerMsgInterface
    {
        return new ServerMsg();
    }
}
