<?php

namespace App\Swoole\Websocket\Server\MessageControllers;

use App\Swoole\Websocket\Server\Connection;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Security\Member;
use App\extensions\MemberExtension;
use App\Swoole\Websocket\Server\ConnectionsPool;
use Swoole\WebSocket\Frame;

abstract class AbstractMessageController
{

    use Configurable, Injectable;

    /** @var ConnectionsPool|null */
    private ?ConnectionsPool $connectionsPool = null;

    /** @var Frame|null */
    private ?Frame $frame = null;

    /** @var Member|MemberExtension|null */
    private Member|MemberExtension|null $member = null;

    /** @var array */
    private array $data = [];

    public function isActionAllowed(string $action): bool
    {
        $public_actions = self::config()->get('public_actions') ?? [];
        return in_array($action, $public_actions);
    }

    public function getConnection(): ?Connection
    {
        return $this->connectionsPool->getByFd($this->frame->fd);
    }

    /**
     * @param ConnectionsPool|null $connectionsPool
     * @return static
     */
    public function setConnectionsPool(?ConnectionsPool $connectionsPool): static
    {
        $this->connectionsPool = $connectionsPool;
        return $this;
    }

    /**
     * @return Frame|null
     */
    public function getFrame(): ?Frame
    {
        return $this->frame;
    }

    /**
     * @param Frame|null $frame
     * @return static
     */
    public function setFrame(?Frame $frame): static
    {
        $this->frame = $frame;
        return $this;
    }

    /**
     * @return MemberExtension|Member|null
     */
    public function getMember(): MemberExtension|Member|null
    {
        return $this->member;
    }

    /**
     * @param MemberExtension|Member|null $member
     * @return static
     */
    public function setMember(MemberExtension|Member|null $member): static
    {
        $this->member = $member;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

}
