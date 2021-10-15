<?php

namespace RedsysConsultasPHP\Model;

/**
 * Store a list of transactions.
 *
 * @package RedsysConsultasPHP\Model
 */
class Transactions extends \ArrayObject {

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        if (!$value instanceof Transaction)
        {
            throw new \InvalidArgumentException(sprintf('Transactions class only allow  %s objects', Transaction::class));
        }
        parent::offsetSet($key, $value);
    }

    /**
     * Generate transaction from xml response from webservice.
     *
     * @param \SimpleXMLElement $xml
     *   XML.
     *
     * @return array $transactions
     *   array of Transaction objects.
     *
     * @throws \Exception
     *   Error if xml does not have data.
     */
    public static function fromXml(\SimpleXMLElement $xml)
    {
        $transactions_data = $xml->xpath('//Messages/Version/Message/Response');
        $transactions = new static();
        foreach (array_values($transactions_data) as $transaction_data) {
            if (empty($transaction_data)) {
                // @TODO: custom exception!
                throw new \Exception('There is no transaction data for iteration '.$i.' in response');
            }

            $transactions[] = Transaction::fromXml($transaction_data[0]);
        }

        return $transactions;
    }

}
