<?php

namespace FastBill\Api;

class BadRequestException extends \RuntimeException
{
    protected $errors = array();

    public static function fromResponse(\stdClass $jsonResponse)
    {
        $msg = 'The Request to the FastBill-API has failed.';

        if (isset($jsonResponse->RESPONSE->ERRORS)) {
            $errors = $jsonResponse->RESPONSE->ERRORS;

            $e = new static($msg.implode("\n", $errors));
            $e->setErrors($errors);
        } else {
            $e = new static($msg);
        }

        return $e;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
