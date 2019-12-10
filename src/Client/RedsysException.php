<?php

namespace RedsysConsultasPHP\Client;

use RedsysConsultasPHP\Client\RedsysErrorInfo;

class RedsysException extends \Exception {

    /**
     * RedsysException constructor.
     *
     * @param $error_code
     */
    public function __construct($error_code)
    {
        $message = $this->getMessageInfo($error_code);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns message info from code.
     *
     * @param $error_code
     *   Error code.
     *
     * @return string
     *   Message info.
     */
    public function getMessageInfo($error_code)
    {
        return 'Error ' . $error_code . ': ' . RedsysErrorInfo::getErrorInfo($error_code);
    }

}
