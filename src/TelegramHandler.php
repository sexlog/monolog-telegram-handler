<?php

namespace sexlog\Monolog;

use Monolog\Logger;
use Monolog\Handler\MailHandler;
use sexlog\Monolog\Interfaces\ProviderInterface;

class TelegramHandler extends MailHandler
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct(ProviderInterface $provider, $level = Logger::CRITICAL, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->provider = $provider;
    }

    /**
     * Send a message with the given content
     *
     * @param string $content formatted email body to be sent
     * @param array  $records the array of log records that formed this content
     */
    protected function send($content, array $records)
    {
        $this->provider->execute($content);
    }
}
