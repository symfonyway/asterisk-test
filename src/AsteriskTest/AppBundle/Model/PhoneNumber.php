<?php

namespace AsteriskTest\AppBundle\Model;

use AsteriskTest\AppBundle\Validator\Constraints as Validator;

class PhoneNumber
{
    /**
     * @var string
     * @Validator\PhoneNumber
     */
    private $phoneNumber;

    public function __construct()
    {
        $this->phoneNumber = '';
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function addSymbolToPhoneNumber($symbol = null)
    {
        $symbol = (string)$symbol;
        if (strlen($symbol)) {
            $this->phoneNumber .= $symbol;
        }
    }
}