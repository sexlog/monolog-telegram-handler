<?php

namespace sexlog\Monolog;

use Monolog\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueConsumer extends Queue
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger, AMQPStreamConnection $connection, $channelName)
    {
        $this->logger = $logger;

        $this->connection = $connection;

        $this->setChannelName($channelName);
    }

    public function listen()
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($this->channelName, false, true, false, false);

        $channel->basic_qos(null, 1, null);

        $channel->basic_consume($this->channelName, '', false, false, false, false, [$this, 'process']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }

    public function process(AMQPMessage $message)
    {
        $handlers = $this->logger->getHandlers();

        if (empty($handlers)) {
            throw new \Exception('No handlers registered');
        }

        $this->logger->critical($message->body);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }
}
