<?php

namespace App\Swoole\Websocket\Messages;

class Message
{
    private string $text = '';

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return static
     */
    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->text
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray()) . '';
    }

}
