<?php

namespace App\Swoole;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use Swoole\WebSocket\Server as SwooleServer;
use Swoole\WebSocket\Frame as SwooleFrame;

class WsServerRunTask extends BuildTask
{

    private static $segment = 'server-ws-swoole-run';

    protected $title = 'Server WS Swoole Run';

    protected $description = 'Server WS Swoole Run Task';

    /**
     * @param HTTPRequest $request
     */
    public function run($request = null)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        Debug::dump('Hello WS Swoole Run Task!!!');

        $server = new SwooleServer('0.0.0.0', 9502);

        $server->on('message', function (SwooleServer $server, SwooleFrame $frame) {

            // frame data comes in as a string
//            $output = json_decode($frame->data, true);
            Debug::dump('Message received');
            Debug::dump($frame->data);
        });

        $server->set([
            // logging
            'log_level' => 1,
            'log_file' => BASE_PATH.'/storage/logs/ws_swoole_server.log',
            'log_rotation' => SWOOLE_LOG_ROTATION_DAILY | SWOOLE_LOG_ROTATION_SINGLE,
            'log_date_format' => true, // or "day %d of %B in the year %Y. Time: %I:%S %p",
            'log_date_with_microseconds' => false,
        ]);

        $server->start();

        Debug::dump('Bye WS Swoole Run Task!!!');
    }


}
