# Redsys query PHP

Allow query order transactions done into spanish gateway banks that uses Sermepa/Redsys.

This is used into webservice located in /apl02/services/SerClsWSConsulta.

The spanish documentation is located in 'TPV-Virtual Consulta SOAP.pdf', in root folder.

Right now, the services covered by this library are:
 - Transacción Simple (Transaction)
 - Transacción Masiva (TransactionMasiva)
 - Monitor Masiva (MonitorMasiva)

## Installation

Use composer:
```bash
composer require codibit/redsys-query-php
```

## Examples

### Get single transaction (TransactionMasiva)
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
$response = $client->getTransactionsByDateRangeAndType($order, $terminal, $merchant_code);

print_r($response);

```
### Get multiple transactions by date (Via Monitor Masiva)
This service allows get the redsys transactions between two dates.

Example:
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

$terminal = 'Introduce your terminal';
$merchant_code = 'Introduce your merchant code';
// Note: Period shouldn't be bigger than 15 days and start date can't be older than 1 year.
$start_date = "2021-05-01-00.00.00.000000"; // Y-m-d-H.i.s.000000
$end_date = "2021-05-14-23.59.59.000000"; // Y-m-d-H.i.s.000000


$response = $client->getTransactionsByDateRange($terminal, $merchant_code, $start_date, $end_date);

print_r($response);

```
### Get multiple transactions by date and type (Via Transaction Masiva)
This service allows get the redsys transactions between two dates and a certain type of transaction.
As far as I can tell, 0 for incoming transactions, 3 for refunds.

Example:
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

$terminal = 'Introduce your terminal';
$merchant_code = 'Introduce your merchant code';
// Note: Period shouldn't be bigger than 15 days and start date can't be older than 1 year.
$start_date = "2021-05-01-00.00.00.000000"; // Y-m-d-H.i.s.000000
$end_date = "2021-05-14-23.59.59.000000"; // Y-m-d-H.i.s.000000

$transaction_type = "0"; // Single integer


$response = $client->getTransactionMasiva($terminal, $merchant_code, $start_date, $end_date, $transaction_type);

print_r($response);
