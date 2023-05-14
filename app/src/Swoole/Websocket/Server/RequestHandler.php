<?php

namespace App\Swoole\Websocket\Server;

use App\Swoole\Utils\StringUtils;
use App\Swoole\Utils\TokenUtils;
use App\Swoole\Websocket\Server\Controllers\AbstractController;
use Swoole\Http\Request;
use Swoole\Http\Response;

class RequestHandler
{

    /** @var Request|null */
    private ?Request $request = null;

    /** @var Response|null */
    private ?Response $response = null;

    /** @var ConnectionsPool|null */
    private ?ConnectionsPool $connectionsPool = null;

    public function handle(): bool
    {
        $request = $this->request;
        $response = $this->response;

        $path = ltrim($request->server['path_info'], '/');
        $exs = explode('/', $path);
        $controllerName = StringUtils::toCamelCase($exs[0] ?? '');
        $controllerClass = __NAMESPACE__ . "\\Controllers\\{$controllerName}Controller";
        if (!class_exists($controllerClass)) {
            return $this->error('Handler not found', 404);
        }
        $action = StringUtils::toCamelCase($exs[1] ?? '') . 'Action';
        if (!method_exists($controllerClass, $action)) {
            return $this->error('Handler action not found', 404);
        }
        /** @var AbstractController $controller */
        $controller = new $controllerClass();
        $controller->setRequest($request)
            ->setResponse($response)
            ->setMember(TokenUtils::authorize($request->header['authorization'] ?? ''))
            ->setConnectionsPool($this->connectionsPool);
        if (!$controller->isActionAllowed($action)) {
            return $this->error('Handler action is not allowed', 403);
        }
        $controller->{$action}();
        return true;
    }

    private function error(string $message, int $code): bool
    {
        $this->response->status($code);
        return $this->response->end($message);
    }

    /**
     * @param Request|null $request
     * @return static
     */
    public function setRequest(?Request $request): static
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param Response|null $response
     * @return static
     */
    public function setResponse(?Response $response): static
    {
        $this->response = $response;
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

}
