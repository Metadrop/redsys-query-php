<?php

require '../vendor/autoload.php';

use RedsysConsultasPHP\Client\Client;

$url = 'https://sis-t.redsys.es:25443/apl02/services/SerClsWSConsulta';
$logger = new Logger('log');
$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler('dir_to_log/name_of_log.log'));
$config = [
    'logger' => $logger,
    'logger_format' => '{request}',
];
$client = new Client($url, 'Introduce your merchant password', $config);

$order = 'Introduce Ds_Order';
$terminal = 'Introduce Ds_terminal';
$merchant_code = 'Introduce your Ds_merchantCode';
$response = $client->getTransaction($order, $terminal, $merchant_code);

print_r($response);
