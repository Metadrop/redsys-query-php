<?php

namespace RedsysConsultasPHP\Model;

/**
 * Base model for a redsys response.
 *
 * @package RedsysConsultasPHP\Model
 */
class BaseModel
{
    protected $Ds_MerchantCode;
    protected $Ds_Terminal;
    protected $Ds_Order;
    protected $Ds_TransactionType;
    protected $Ds_Date;
    protected $Ds_Hour;
    protected $Ds_Amount;
    protected $Ds_Currency;
    protected $Ds_SecurePayment;
    protected $Ds_State;
    protected $Ds_Response;

    /**
     * FUC Code assigned to commerce.
     *
     * @return string
     *   Merchant code.
     */
    public function getDsMerchantCode()
    {
        return $this->Ds_MerchantCode;
    }

    /**
     * Set merchant code.
     *
     * @param string $Ds_MerchantCode
     *   Merchant code.
     */
    public function setDsMerchantCode($Ds_MerchantCode)
    {
        $this->Ds_MerchantCode = $Ds_MerchantCode;
    }

    /**
     * @return mixed
     */
    public function getDsTerminal()
    {
        return $this->Ds_Terminal;
    }

    /**
     * @param mixed $Ds_Terminal
     */
    public function setDsTerminal($Ds_Terminal)
    {
        $this->Ds_Terminal = $Ds_Terminal;
    }

    /**
     * Get order identifier.
     *
     * @return int
     *   Order identifier.
     */
    public function getDsOrder()
    {
        return $this->Ds_Order;
    }

    /**
     * Set order identifier.
     *
     * @param int $Ds_order
     *   Order identifier.
     */
    public function setDsOrder($Ds_Order)
    {
        $this->Ds_Order = $Ds_Order;
    }

    /**
     * Get transaction type.
     *
     * @return int
     *   Transaction type.
     */
    public function getDsTransactionType()
    {
        return $this->Ds_TransactionType;
    }

    /**
     * Get transaction type.
     *
     * @param int $Ds_TransactionType
     *   Transaction type.
     */
    public function setDsTransactionType($Ds_TransactionType)
    {
        $this->Ds_TransactionType = $Ds_TransactionType;
    }

    /**
     * @return mixed
     */
    public function getDsDate()
    {
        return $this->Ds_Date;
    }

    /**
     * Set order date.
     *
     * @param int $Ds_Date
     *   Order date.
     */
    public function setDsDate($Ds_Date)
    {
        $this->Ds_Date = $Ds_Date;
    }

    /**
     * Get order hour.
     *
     * @return string
     *   Order hour.
     */
    public function getDsHour()
    {
        return $this->Ds_Hour;
    }

    /**
     * @param mixed $Ds_Hour
     */
    public function setDsHour($Ds_Hour)
    {
        $this->Ds_Hour = $Ds_Hour;
    }

    /**
     * Get order amount.
     *
     * @return string
     *   Order amount.
     */
    public function getDsAmount()
    {
        return $this->Ds_Amount;
    }

    /**
     * Set order amount.
     *
     * @param int $Ds_Amount
     *   Order amount.
     */
    public function setDsAmount($Ds_Amount)
    {
        $this->Ds_Amount = $Ds_Amount;
    }

    /**
     * @return mixed
     */
    public function getDsCurrency()
    {
        return $this->Ds_Currency;
    }

    /**
     * @param mixed $Ds_Currency
     */
    public function setDsCurrency($Ds_Currency)
    {
        $this->Ds_Currency = $Ds_Currency;
    }

    /**
     * @return mixed
     */
    public function getDsSecurePayment()
    {
        return $this->Ds_SecurePayment;
    }

    /**
     * @param mixed $Ds_SecurePayment
     */
    public function setDsSecurePayment($Ds_SecurePayment)
    {
        $this->Ds_SecurePayment = $Ds_SecurePayment;
    }

    /**
     * @return mixed
     */
    public function getDsState()
    {
        return $this->Ds_State;
    }

    /**
     * @param mixed $Ds_State
     */
    public function setDsState($Ds_State)
    {
        $this->Ds_State = $Ds_State;
    }

    /**
     * @return mixed
     */
    public function getDsResponse()
    {
        return $this->Ds_Response;
    }

    /**
     * @param mixed $Ds_Response
     */
    public function setDsResponse($Ds_Response)
    {
        $this->Ds_Response = $Ds_Response;
    }

    /**
     * Returns the object as an array.
     *
     * @return array
     *   Object as an array.
     */
    public function toArray()
    {
        $values = [];
        foreach (RedsysFields::getList() as $field) {
            $field_getter_method = 'get' . str_replace('_', '', $field);
            if (method_exists($this, $field_getter_method)) {
                $values[$field] = $this->{$field_getter_method}();
            }
        }
        return $values;
    }

}
