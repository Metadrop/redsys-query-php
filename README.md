# Redsys query PHP

Allow query order transactions done into spanish gateway banks that uses Sermepa/Redsys.

This is used into webservice located in /apl02/services/SerClsWSConsulta.

The spanish documentation is located in 'Consultas SOAP HMAC SHA256 2.5', in root folder.

@IMPORTANT: Right now, the only services covered by this library are  'Transacción Simple' and 'Monitor Masiva'.

## Installation

Use composer:
```bash
composer require metadrop/redsys-consultas-php
```

## Examples

### Get single transaction
This service allows get the redsys transactions by id. 

Example (also available in examples/get_transaction.php:


```php

<?php
require './vendor/autoload.php';

use RedsysConsultasPHP\Client\Client;

$url = 'https://sis-t.redsys.es:25443/apl02/services/SerClsWSConsulta';
$logger = new \Monolog\Logger;('log');
$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler('dir_to_log/name_of_log.log'));
$config = [
  'logger' => $logger,
  'logger_format' => '{request}',
];
$client = new Client($url, 'Introduce your merchant password', $config);

$order = 'Introduce your order';
$terminal = 'Introduce your terminal';
$merchant_code = 'Introduce your merchant code';
$response = $client->getTransaction($order, $terminal, $merchant_code);

print_r($response);

```
