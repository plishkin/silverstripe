<?php

namespace App\Swoole\Websocket\Server;

use App\Swoole\Utils\StringUtils;
use App\Swoole\Utils\TokenUtils;
use App\Swoole\Websocket\Server\MessageControllers\AbstractMessageController;
use Swoole\WebSocket\Frame;

class MessageHandler
{

    /** @var ConnectionsPool|null */
    private ?ConnectionsPool $connectionsPool = null;

    /** @var Frame|null */
    private ?Frame $frame = null;

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
     * @param ConnectionsPool|null $connectionsPool
     * @return static
     */
    public function setConnectionsPool(?ConnectionsPool $connectionsPool): static
    {
        $this->connectionsPool = $connectionsPool;
        return $this;
    }

    public function handle(): bool
    {
        $frame = $this->frame;
        $data = json_decode($frame->data, true);
        if (!$data || empty($data['handler']) || empty($data['action'])) {
            return $this->error('Handler not found');
        }
        $controllerName = StringUtils::toCamelCase($data['handler']);
        $controllerClass = __NAMESPACE__ . "\\MessageControllers\\{$controllerName}Controller";
        if (!class_exists($controllerClass)) {
            return $this->error("Handler \"$controllerName\"not found");
        }
        $action = StringUtils::toCamelCase($data['action']) . 'Action';
        if (!method_exists($controllerClass, $action)) {
            return $this->error("Handler action \"$action\" not found");
        }
        /** @var AbstractMessageController $controller */
        $controller = new $controllerClass();
        $controller->setFrame($frame)
            ->setData($data['data'] ?? [])
            ->setMember(TokenUtils::authorize($data['authorization'] ?? ''))
            ->setConnectionsPool($this->connectionsPool);
        if (!$controller->isActionAllowed($action)) {
            return $this->error("Handler action \"$action\"is not allowed");
        }
        $controller->{$action}();
        return true;
    }

    private function error(string $message): bool
    {
        echo "$message\n";
        return false;
    }

}
