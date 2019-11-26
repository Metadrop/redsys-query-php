<?php

namespace RedsysConsultasPHP\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use RedsysConsultasPHP\Model\Transaction;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use Monolog\Handler\RotatingFileHandler;
use Psr\Log\LoggerInterface;

/**
 * Client to make queries to redsys query webservice.
 *
 * @package RedsysConsultasPHP\Client
 */
class Client extends GuzzleClient
{
    /**
     * Url of redsys test environment.
     */
    const WEBSERVICE_URL_TESTING = 'https://sis-t.redsys.es:25443/apl02/services/SerClsWSConsulta';

    /**
     * Webservice URL.
     *
     * @var string
     */
    protected $webserviceUrl;

    /**
     * Request generator.
     *
     * @var RequestGenerator
     */
    protected $requestGenerator;

    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Client constructor.
     *
     * Example config:
     * $config = [
     *   'logger' => new \Monolog\Logger('log'),
     *   'logger_format' => '{request}',
     * ];
     * @see https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php#L14
     *
     * @param string $webservice_url
     *   Webservice url.
     * @param string $trade_key
     *   Trade key.
     * @param array $config
     *   Configuration.
     */
    public function __construct($webservice_url, $trade_key, array $config = [])
    {
        $this->webserviceUrl = $webservice_url;
        $this->requestGenerator = new RequestGenerator($trade_key);

        if (isset($config['logger']) && $config['logger'] instanceof LoggerInterface) {
            $this->logger = $config['logger'];
            $stack = HandlerStack::create();
            // Logger, message format.
            $messageFormat = isset($config['logger_format']) ? $config['logger_format'] : '{request}';
            $stack->push(
                $this->createGuzzleLoggingMiddleware($messageFormat)
            );
            $config['handler'] = $stack;
        }
        parent::__construct($config);
    }

    /**
     * Create middleware guzzle log to capture petition info.
     *
     * @param string $messageFormat
     *   Message format.
     *
     * @return callable
     *   Middleware.
     */
    private function createGuzzleLoggingMiddleware(string $messageFormat)
    {
        return Middleware::log(
            $this->logger,
            new MessageFormatter($messageFormat)
        );
    }

    /**
     * This headers will be sent on every request.
     *
     * @return array
     *   List of headers, beng the most important 'SOAPAction'.
     */
    protected function defaultHeaders()
    {
        return [
            'Content-type' => 'text/xml;charset=utf-8',
            'Accept' => ' text/xml',
            'Cache-Control' => ' no-cache',
            'Pragma' => ' no-cache',
            'SOAPAction' => 'consultaOperaciones',
        ];
    }

    /**
     * Get a transaction.
     *
     * @param int $order_id
     *   Order id.
     * @param $terminal
     *   Terminal.
     * @param $merchant_code
     *   Merchant code.
     * @param int $transaction_type
     *   Transaction type.
     *
     * @return Transaction
     *   Transaction.
     */
    public function getTransaction($order_id, $terminal, $merchant_code, $transaction_type = 0)
    {
        $payload = $this->buildPayload($this->requestGenerator->transaction($order_id, $terminal, $merchant_code, $transaction_type));
        $response = $this->doRequest($payload);
        return !empty($response) ? Transaction::fromXml($response) : NULL;
    }

    /**
     * Do request to webservice.
     *
     * @param $payload
     *   Payload.
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     *   Response.
     *
     * @throws \Exception
     */
    private function doRequest($payload)
    {
        $headers = $this->defaultHeaders() + [
            'Content-length' => strlen($payload),
        ];
        try {
            $response = $this->post($this->webserviceUrl, [RequestOptions::HEADERS => $headers, RequestOptions::BODY => $payload]);
            $response = ResponseParser::parse($response);
            if (count($response->xpath('//Messages/Version/Message/ErrorMsg/Ds_ErrorCode')) == 1) {
                list($error_code) = $response->xpath('//ErrorMsg/Ds_ErrorCode');
                // @TODO: wrap response into an exception class to show every exception code!
                throw new \Exception('Error ' . $error_code);
            }
        } catch (RequestException $e) {
            $response = null;
        }
        return $response;
    }

    /**
     * Transform the xml payload we will send to webservice into a soap request.
     *
     * @param string $payload
     *   Soap request payload.
     *
     * @return string
     *   Soap full payload.
     */
    protected function buildPayload($payload)
    {
        $soap_request  = "<?xml version=\"1.0\"?>\n";
        $soap_request .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webservices.apl02.redsys.es">';
        $soap_request .= '<soapenv:Header/>';
        $soap_request .= '<soapenv:Body>';
        $soap_request .= '<web:consultaOperaciones>';
        $soap_request .= '<cadenaXML>';
        $soap_request .= '<![CDATA['.$payload.']]>';
        $soap_request .= '</cadenaXML>';
        $soap_request .= '</web:consultaOperaciones>';
        $soap_request .= '</soapenv:Body>';
        $soap_request .= '</soapenv:Envelope>';

        return $soap_request;
    }
}
