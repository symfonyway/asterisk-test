<?php

namespace AsteriskTest\AppBundle\Tests\Validator\Constraints;

use AsteriskTest\AppBundle\Validator\Constraints\PhoneNumber;
use AsteriskTest\AppBundle\Validator\Constraints\PhoneNumberValidator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class PhoneNumberValidatorTest extends TestCase
{
    public function testInstanceOf()
    {
        $validator = new PhoneNumberValidator();
        $this->assertInstanceOf('Symfony\Component\Validator\ConstraintValidatorInterface', $validator);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate($value, $violates)
    {
        $validator = new PhoneNumberValidator();
        $constraint = new PhoneNumber();

        if (class_exists('Symfony\Component\Validator\Context\ExecutionContext')) {
            $executionContextClass = 'Symfony\Component\Validator\Context\ExecutionContext';
        } else {
            $executionContextClass = 'Symfony\Component\Validator\ExecutionContext';
        }
        $context = $this->getMockBuilder($executionContextClass)
            ->disableOriginalConstructor()->getMock();

        $constraintViolationBuilder = $this->getMockBuilder('Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface')
            ->disableOriginalConstructor()->getMock();

        $validator->initialize($context);

        if (false === $violates) {
            $context
                ->expects($this->once())->method('buildViolation')
                ->with($constraint->message)
                ->will($this->returnValue($constraintViolationBuilder))
            ;
            $constraintViolationBuilder
                ->expects($this->once())->method('setParameter')
                ->with('%phone%', $value)
                ->will($this->returnValue($constraintViolationBuilder))
            ;
            $constraintViolationBuilder
                ->expects($this->once())->method('addViolation');
        } else {
            $context->expects($this->never())->method('addViolation');
        }

        $validator->validate($value, $constraint);
    }

    public function validateProvider()
    {
        return array(
            array('+1-234-234-2345', true),
            array('+1-234-2342345', true),
            array('1-234-2342345', true),
            array('1-234-23423-45', false),
            array('+1-234-234w-2345', false),
            array('+1-234-2345-2345', false),
            array('', false),
            array('+441234567890', false),
            array('foo', false),
        );
    }

    /**
     * @dataProvider validateThrowsUnexpectedTypeExceptionProvider
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testValidateThrowsUnexpectedTypeException($value)
    {
        $validator = new PhoneNumberValidator();
        $constraint = new PhoneNumber();
        $validator->validate($value, $constraint);
    }

    public function validateThrowsUnexpectedTypeExceptionProvider()
    {
        return array(
            array(null),
            array(new PhoneNumber()),
            array($this),
        );
    }
}
