<?php

namespace App\Swoole\Websocket\Server\Controllers;

use App\Swoole\Websocket\Messages\TimerMessage;

class EventController extends AbstractController
{

    public function TimerAction()
    {
        $pool = $this->getConnectionsPool();
        $maxC = 0;
        foreach ($pool->getConnections() as $connection) {
            $c = $connection->data['countdown'] ?? 0;
            if ($c > 0) {
                $c--;
                $maxC = max($c, $maxC);
                $connection->data['countdown'] = $c;
                $message = new TimerMessage();
                $message->setText('Timer ticks')
                    ->setCountdown($c);
                $connection->sendMessage($message);
            }
        }
        $this->getResponse()->end(json_encode([
            'maxCountdown' => $maxC,
        ]));
    }

    public function isActionAllowed(string $action): bool
    {
        $member = $this->getMember();
        return $member->isLocalMember();
    }

}
