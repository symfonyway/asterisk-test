<?php

namespace AsteriskTest\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhoneNumber extends Constraint
{
    public $message = 'Value "%phone%" is not a valid phone number.';
}