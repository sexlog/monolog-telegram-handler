<?php

namespace sexlog\Monolog;

use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class Queue
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $channelName;

    /**
     * @param string $channelName
     */
    public function setChannelName($channelName)
    {
        if (empty($channelName)) {
            throw new \InvalidArgumentException('Invalid channel name');
        }

        $this->channelName = $channelName;
    }
}
