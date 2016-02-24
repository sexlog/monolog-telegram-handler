<?php

use GuzzleHttp\Client;
use Monolog\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use sexlog\Monolog\Providers\HttpProvider;
use sexlog\Monolog\QueueConsumer;
use sexlog\Monolog\TelegramHandler;

require(__DIR__ . '/../vendor/autoload.php');

$host = '172.16.0.10';
$port = 5672;
$user = 'guest';
$pass = 'guest';

// Creates a connection with do RabbitMQ server
$queue = new AMQPStreamConnection($host, $port, $user, $pass);

$http   = new Client(['verify' => false]);
$token  = '[your_api_token]';
$chatId = '[chat_id]';

$provider = new HttpProvider($http, $token, $chatId);

$handler = new TelegramHandler($provider);

$logger = new Logger('example');
$logger->pushHandler($handler);

$receiver = new QueueConsumer($logger, $queue, 'example');
$receiver->listen();
