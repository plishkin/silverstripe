<?php

namespace App\Swoole\Websocket\Server\MessageControllers;

use App\Swoole\Websocket\TimerTask;

class TimerController extends AbstractMessageController
{

    public function StartAction()
    {
        $connection = $this->getConnection();
        $connection->data['countdown'] = 120;
        TimerTask::process();
    }

    public function isActionAllowed(string $action): bool
    {
        return $action === 'StartAction';
    }

}
