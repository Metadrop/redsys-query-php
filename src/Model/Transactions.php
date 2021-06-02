<?php

namespace RedsysConsultasPHP\Model;

/**
 * Class Transactions
 * @package RedsysConsultasPHP\Model
 */
class Transactions extends BaseModel
{

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
        $transactions = array();
        $i = 0;

        foreach($transactions_data as $transaction_data){

            $transactions[$i] = new static();

            if (empty($transaction_data)) {
                // @TODO: custom exception!
                throw new \Exception('There is no transaction data for iteration '.$i.' in response');
            }

            $transaction_xml = $transaction_data[0];

            foreach (RedsysFields::getList() as $field) {
                $field_setter_method = 'set' . str_replace('_', '', $field);
                if (method_exists($transactions[$i], $field_setter_method) && isset($transaction_xml->{$field})) {
                    $transactions[$i]->{$field_setter_method}((string) $transaction_xml->{$field});
                }
            }

            $i++;

        }
        return $transactions;
    }
}
