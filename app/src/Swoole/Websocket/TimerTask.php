<?php

namespace App\Swoole\Websocket;

use App\Swoole\Utils\TokenUtils;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;

class TimerTask extends BuildTask
{
    const PID_FILE = 'timer-ws.pid';

    private static string $segment = 'timer-ws';

    private static function getPidFilePath(): string
    {
        return TEMP_FOLDER . '/' . self::PID_FILE;
    }

    private static function runShell($cmd)
    {
        $log = BASE_PATH . '/storage/logs/timer-ws.log';
        $pids = BASE_PATH . '/storage/logs/timer-ws-pids.log';
        $cmd = sprintf("%s > \"%s\" 2>&1 & echo $! >> \"%s\"", $cmd, $log, $pids);
        exec($cmd);
    }

    public static function isRunning(): bool
    {
        $path = self::getPidFilePath();
        $pid = is_file($path) ? (int)\file_get_contents($path) : null;
        if (!$pid) {
            return false;
        }
        \exec("ps {$pid}", $pState);
        return \count($pState) >= 2;
    }

    public static function process()
    {
        if (self::isRunning()) {
            return false;
        }
        $cmd = 'sake dev/tasks/' . self::$segment;
        self::runShell($cmd);
    }

    protected $title = 'Timer Websockets';

    protected $description = 'Timer Websockets Task';

    /**
     * @param HTTPRequest $request
     */
    public function run($request = null)
    {
        file_put_contents(self::getPidFilePath(), getmypid());
        echo "Hello Timer Run Task!!!\n";

        $url = 'http://websockets:6001/event/timer';
        $token = TokenUtils::getLocalToken();
        $context = stream_context_create(['http' => ['header' => "Authorization: $token"]]);

        $maxCountdown = 1;
        $maxAfterSteps = $steps = 6;
        $c = 0;
        while ($maxCountdown > 0 || $steps > 0) {
            $data = file_get_contents($url, false, $context);
            $json = json_decode($data, true);
            $maxCountdown = $json['maxCountdown'] ?? 0;
            $steps = $maxCountdown > 0 ? $maxAfterSteps : $steps - 1;
            sleep(1);
            $c++;
        }
        echo "$c steps done\n";
        echo "Bye Timer Run Task!!!\n";
        file_put_contents(self::getPidFilePath(), getmypid() . '_FINISHED');
    }

}
