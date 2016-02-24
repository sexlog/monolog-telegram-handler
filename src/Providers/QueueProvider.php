<?php

namespace sexlog\Monolog\Providers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use sexlog\Monolog\Interfaces\ProviderInterface;
use sexlog\Monolog\Queue;

class QueueProvider extends Queue implements ProviderInterface
{
    public function __construct(AMQPStreamConnection $connection, $channelName)
    {
        $this->connection = $connection;

        $this->setChannelName($channelName);
    }

    public function execute($content)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($this->channelName, false, true, false, false);

        // Persistent delivery
        $message = new AMQPMessage($content, ['delivery_mode' > 2]);

        $channel->basic_publish($message, '', $this->channelName);

        $channel->close();
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
