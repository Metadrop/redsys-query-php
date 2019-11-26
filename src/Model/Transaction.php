<?php

namespace RedsysConsultasPHP\Model;

/**
 * Class Transaction
 * @package RedsysConsultasPHP\Model
 */
class Transaction extends BaseModel
{

    /**
     * Generate transaction from xml response from webservice.
     *
     * @param \SimpleXMLElement $xml
     *   XML.
     *
     * @return Transaction
     *   Transaction.
     *
     * @throws \Exception
     *   Error if xml does not have data.
     */
    public static function fromXml(\SimpleXMLElement $xml)
    {
        $transaction_data = $xml->xpath('//Messages/Version/Message/Response');

        $transaction = new static();

        if (empty($transaction_data)) {
            // @TODO: custom exception!
            throw new \Exception('There is no transaction data in response');
        }

        $transaction_xml = $transaction_data[0];

        foreach (RedsysFields::getList() as $field) {
            $field_setter_method = 'set' . str_replace('_', '', $field);
            if (method_exists($transaction, $field_setter_method) && isset($transaction_xml->{$field})) {
                $transaction->{$field_setter_method}((string) $transaction_xml->{$field});
            }
        }

        return $transaction;
    }
}
