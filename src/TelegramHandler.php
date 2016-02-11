<?php

namespace sexlog\Monolog;

use GuzzleHttp\Client;
use Monolog\Handler\MailHandler;
use Monolog\Logger;

class TelegramHandler extends MailHandler
{
    /**
     * @var Client
     */
    private $http;

    /**
     * @var string
     */
    private $endpoint = 'https://api.telegram.org/bot[token]';

    /**
     * @var int
     */
    private $chatId;

    public function __construct(Client $client, $token, $chatId, $level = Logger::CRITICAL, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->http = $client;

        $this->endpoint = str_replace('[token]', $token, $this->endpoint);

        $this->chatId = $chatId;
    }

    /**
     * @param int $chatId
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }

    /**
     * Send a message with the given content
     *
     * @param string $content formatted email body to be sent
     * @param array  $records the array of log records that formed this content
     */
    protected function send($content, array $records)
    {
        $sendMessageEndpoint = $this->endpoint . '/sendMessage';

        $body = [
            'chat_id' => $this->chatId,
            'text' => $content,
            'disable_web_page_preview' => true
        ];

        $this->http->post($sendMessageEndpoint, ['form_data' => $body]);
    }
}
