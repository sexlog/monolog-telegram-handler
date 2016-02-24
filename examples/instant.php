<?php

use GuzzleHttp\Client;
use Monolog\Logger;
use sexlog\Monolog\Providers\HttpProvider;
use sexlog\Monolog\TelegramHandler;

require(__DIR__ . '/../vendor/autoload.php');

$http   = new Client(['verify' => false]);
$token  = '[your_api_token]';
$chatId = '[chat_id]';

// Registering the provider which will make a direct call do Telegram API
$provider = new HttpProvider($http, $token, $chatId);

// Register the handler
$handler = new TelegramHandler($provider, Logger::INFO);

$logger = new Logger('example');
$logger->pushHandler($handler);

$logger->info('Faith, collision course, and coordinates.');
