<?php

namespace RedsysConsultasPHP\Client;

use RedsysConsultasPHP\Client\RedsysErrorInfo;

/**
 * Exception that it's thrown when there is a problem with redsys during requests.
 */
class RedsysException extends \Exception {

    /**
     * RedsysException constructor.
     *
     * @param string $error_code
     *   Error code.
     */
    public function __construct($error_code)
    {
        $message = $this->getMessageInfo($error_code);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Obtain the human readable message from redsys error code.
     *
     * @param $error_code
     *   Redsys error code.
     *
     * @return string
     *   Message info.
     */
    public function getMessageInfo($error_code)
    {
        return 'Error ' . $error_code . ': ' . RedsysErrorInfo::getErrorInfo($error_code);
    }

}
