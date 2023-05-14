<?php

namespace App\Swoole\Websocket;

use App\extensions\MemberExtension;
use App\Swoole\Websocket\Server\ConnectionsPool;
use App\Swoole\Websocket\Server\MessageHandler;
use App\Swoole\Websocket\Server\RequestHandler;
use SilverStripe\Security\Member;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class Server
 * @package App\Swoole
 */
class ServerHandler
{

    /**
     * @param int $port
     * @param string $hostname
     * @return void
     */
    public static function startServer(int $port = 6001, string $hostname = "websockets"): void
    {
        (new static($port, $hostname))->start();
    }

    /** @var int */
    private int $port;

    /** @var string */
    private string $hostname;

    /**
     * @param int $port
     * @param string $hostname
     */
    public function __construct(int $port = 6001, string $hostname = 'websockets')
    {
        $this->port = $port;
        $this->hostname = $hostname;
    }

    private $fds = [];

    public function start(): void
    {
        $server = new Server('0.0.0.0', $this->port);
        $connectionsPool = new ConnectionsPool();
        $connectionsPool->setServer($server);
        $server->on('start', function (Server $server) {
            echo "Websocket server is started at http://$this->hostname:$this->port\n";
        });
        $server->on('open', function (Server $server, Request $request) use ($connectionsPool) {
            echo "Websocket server opened {$request->fd}\n";
            $connectionsPool->add($request);
        });
        // we can also run a regular HTTP server at the same time!
        $server->on('request', function (Request $request, Response $response) use ($connectionsPool) {
            $handler = new RequestHandler();
            $handler->setRequest($request)
                ->setResponse($response)
                ->setConnectionsPool($connectionsPool);
            $handler->handle();
        });
        $server->on('message', function (Server $server, Frame $frame) use ($connectionsPool) {
            echo "Websocket {$frame->fd} received a message {$frame->data}\n";
            $this->fds[$frame->fd] = $frame->fd;
            $handler = new MessageHandler();
            $handler->setFrame($frame)
                ->setConnectionsPool($connectionsPool);
            $handler->handle();
        });
        $server->on('close', function (Server $server, int $fd) use ($connectionsPool) {
            if (isset($this->fds[$fd])) echo "Websocket {$fd} closed\n";
            $connectionsPool->removeByFd($fd);
        });
        $server->set([
            // logging
            'log_level' => 1,
            'log_file' => BASE_PATH . '/storage/logs/ws_swoole_server.log',
            'log_rotation' => SWOOLE_LOG_ROTATION_DAILY | SWOOLE_LOG_ROTATION_SINGLE,
            'log_date_format' => true, // or "day %d of %B in the year %Y. Time: %I:%S %p",
            'log_date_with_microseconds' => false,
        ]);
        $server->start();
    }

}
