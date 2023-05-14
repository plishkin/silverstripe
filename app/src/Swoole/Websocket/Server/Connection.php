<?php

namespace App\Swoole\Websocket\Server;

use App\extensions\MemberExtension;
use App\Swoole\Websocket\Messages\Message;
use SilverStripe\Security\Member;

class Connection
{

    /** @var int */
    private int $fd;

    /** @var Member|MemberExtension */
    private Member|MemberExtension $member;

    /** @var ConnectionsPool */
    private ConnectionsPool $connectionsPool;

    /** @var array */
    public $data = [];

    public function sendMessage(Message $message): void
    {
        $this->connectionsPool->sendMessage($this->fd, $message->toJson());
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return Member|MemberExtension
     */
    public function getMember(): Member|MemberExtension
    {
        return $this->member;
    }

    /**
     * @param ConnectionsPool $connectionsPool
     * @return static
     */
    public function setConnectionsPool(ConnectionsPool $connectionsPool): static
    {
        $this->connectionsPool = $connectionsPool;
        return $this;
    }

    /**
     * @param int $fd
     * @return static
     */
    public function setFd(int $fd): static
    {
        $this->fd = $fd;
        return $this;
    }

    /**
     * @param MemberExtension|Member $member
     * @return static
     */
    public function setMember(MemberExtension|Member $member): static
    {
        $this->member = $member;
        return $this;
    }

}
