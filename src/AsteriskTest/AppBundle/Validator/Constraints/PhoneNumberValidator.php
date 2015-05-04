<?php

namespace AsteriskTest\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneNumberValidator extends ConstraintValidator
{
    const REGEX_NANP_FORMAT = '/([\+]?1[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[0-9]{4})$/';

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param PhoneNumber $constraint The constraint for the validation
     *
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match(self::REGEX_NANP_FORMAT, $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%phone%', $value)
                ->addViolation()
            ;

            return;
        }
    }
}