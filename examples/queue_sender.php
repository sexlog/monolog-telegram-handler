<?php

use Monolog\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use sexlog\Monolog\Providers\QueueProvider;
use sexlog\Monolog\TelegramHandler;

require(__DIR__ . '/../vendor/autoload.php');

$host = 'localhost';
$port = 5672;
$user = 'guest';
$pass = 'guest';

// Creates a connection with do RabbitMQ server
$queueConnection = new AMQPStreamConnection($host, $port, $user, $pass);

// Register the provider which will enqueue the messages to be sent to Telegram
$provider = new QueueProvider($queueConnection, 'example');

// Register the handler
$handler = new TelegramHandler($provider, Logger::INFO);

$logger = new Logger('example');
$logger->pushHandler($handler);

$logger->info('Faith, collision course, and coordinates.');
