<?php

namespace App\Swoole\Websocket\Messages;

class TimerMessage extends Message
{
    private int $countdown = 0;

    /**
     * @return int
     */
    public function getCountdown(): int
    {
        return $this->countdown;
    }

    /**
     * @param int $countdown
     * @return static
     */
    public function setCountdown(int $countdown): static
    {
        $this->countdown = $countdown;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'countdown' => $this->countdown
            ],
        );
    }

}
