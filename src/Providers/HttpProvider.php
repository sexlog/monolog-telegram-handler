<?php

namespace sexlog\Monolog\Providers;

use GuzzleHttp\Client;
use sexlog\Monolog\Interfaces\ProviderInterface;

class HttpProvider implements ProviderInterface
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

    public function __construct(Client $http, $token, $chatId)
    {
        $this->http = $http;

        $this->endpoint = str_replace('[token]', $token, $this->endpoint);

        $this->chatId = $chatId;
    }

    public function execute($content)
    {
        $sendMessageEndpoint = $this->endpoint . '/sendMessage';

        $body = [
            'chat_id' => $this->chatId,
            'text' => $content,
            'disable_web_page_preview' => true,
        ];

        $this->http->post($sendMessageEndpoint, ['form_params' => $body]);
    }
}
