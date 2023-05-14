<?php

namespace App\Swoole\Websocket;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;

class ServerRunTask extends BuildTask
{

    private static string $segment = 'server-ws-swoole-run';

    protected $title = 'Server WS Swoole Run';

    protected $description = 'Server WS Swoole Run Task';

    /**
     * @param HTTPRequest $request
     */
    public function run($request = null)
    {
        echo "Hello WS Swoole Run Task!!!\n";

        ServerHandler::startServer();

        echo "Bye WS Swoole Run Task!!!\n";
    }


}
