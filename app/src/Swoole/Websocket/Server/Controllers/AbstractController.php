<?php

namespace App\Swoole\Websocket\Server\Controllers;

use Swoole\Http\Request;
use Swoole\Http\Response;
use App\Swoole\Websocket\Server\ConnectionsPool;
use SilverStripe\Security\Member;
use App\extensions\MemberExtension;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

abstract class AbstractController
{

    use Configurable, Injectable;

    /** @var ConnectionsPool|null */
    private ?ConnectionsPool $connectionsPool = null;

    /** @var Request|null */
    private ?Request $request = null;

    /** @var Response|null */
    private ?Response $response = null;

    /** @var Member|MemberExtension|null */
    private Member|MemberExtension|null $member = null;

    public function isActionAllowed(string $action): bool
    {
        $public_actions = self::config()->get('public_actions') ?? [];
        return in_array($action, $public_actions);
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
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
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
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
     * @return ConnectionsPool|null
     */
    public function getConnectionsPool(): ?ConnectionsPool
    {
        return $this->connectionsPool;
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
