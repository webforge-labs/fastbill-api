<?php

namespace FastBill\Api;

class BadRequestException extends \RuntimeException
{

    protected $errors = array();

    public static function fromResponse(\stdClass $jsonResponse)
    {
        $e = new static('The Request to the FastBill-API has failed.');
        if (isset($jsonResponse->RESPONSE->ERRORS)) {
            $e->setErrors($jsonResponse->RESPONSE->ERRORS);
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