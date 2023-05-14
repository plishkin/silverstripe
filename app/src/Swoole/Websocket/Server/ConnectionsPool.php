<?php

namespace App\Swoole\Websocket\Server;

use App\Swoole\Utils\TokenUtils;
use SilverStripe\Security\Member;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;

class ConnectionsPool
{

    /** @var Server|null */
    private ?Server $server = null;

    /** @var Connection[] */
    private array $connections = [];

    public function add(Request $request): void
    {
        $connection = new Connection();
        $connection->setFd($request->fd)
            ->setMember(TokenUtils::authorize($request->header['authorization'] ?? ''))
            ->setConnectionsPool($this);
        $this->connections[] = $connection;
    }

    public function getByFd(int $fd): ?Connection
    {
        $conn = null;
        foreach ($this->connections as $connection) {
            if ($connection->getFd() === $fd) {
                $conn = $connection;
                break;
            }
        }
        return $conn;
    }

    public function getByMember(Member $member): ?Connection
    {
        $conn = null;
        foreach ($this->connections as $connection) {
            if ($connection->getMember()->ID === $member->ID) {
                $conn = $connection;
                break;
            }
        }
        return $conn;
    }

    public function removeByFd(int $fd): void
    {
        foreach ($this->connections as $k => $connection) {
            if ($connection->getFd() === $fd) {
                unset($this->connections[$k]);
                break;
            }
        }
    }

    public function sendMessage(int $fd, string $content): void
    {
        $this->server->push($fd, $content);
    }

    /**
     * @param Server $server
     * @return static
     */
    public function setServer(Server $server): static
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return Connection[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }

}
