<?php

namespace AsteriskTest\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneNumberValidator extends ConstraintValidator
{
    const REGEX_NANP_FORMAT = '/([\+]?1[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[2-9]{1}[0-9]{2}[\-\. ]?[0-9]{4})$/';

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param PhoneNumber|Constraint $constraint The constraint for the validation
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!preg_match(self::REGEX_NANP_FORMAT, $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%phone%', $value)
                ->addViolation()
            ;
        }
    }
}