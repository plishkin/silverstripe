<?php

namespace App\Swoole\Http;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Swoole\Http\Server as SwooleServer;

class ServerRunTask extends BuildTask
{

    private static $segment = 'server-http-swoole-run';

    protected $title = 'Server Http Swoole Run';

    protected $description = 'Server Http Swoole Run Task';

    /**
     * @param HTTPRequest $request
     */
    public function run($request = null)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        Debug::dump('Hello Http Swoole Run Task!!!');

        $server = new SwooleServer('0.0.0.0', 80);

        $server->on('request', function (SwooleRequest $request, SwooleResponse $response) {
            Server::handleRequest($request, $response);
        });

        $server->set([
            // logging
            'log_level' => 1,
            'log_file' => BASE_PATH.'/storage/logs/http_swoole_server.log',
            'log_rotation' => SWOOLE_LOG_ROTATION_DAILY | SWOOLE_LOG_ROTATION_SINGLE,
            'log_date_format' => true, // or "day %d of %B in the year %Y. Time: %I:%S %p",
            'log_date_with_microseconds' => false,
        ]);

        $server->start();

        Debug::dump('Bye Http Swoole Run Task!!!');
    }


}
