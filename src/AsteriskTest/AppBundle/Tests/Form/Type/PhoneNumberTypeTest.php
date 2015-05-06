<?php

namespace AsteriskTest\AppBundle\Tests\Form\Type;

use AsteriskTest\AppBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

class PhoneNumberTypeTest extends TypeTestCase
{
    public function setUp()
    {
        parent::setUp();

        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(
                new FormTypeValidatorExtension(
                    $validator
                )
            )
            ->addTypeGuesser(
                $this->getMockBuilder(
                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
                )
                    ->disableOriginalConstructor()
                    ->getMock()
            )
            ->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * @dataProvider defaultFormattingProvider
     */
    public function testDefaultFormatting($input, $output)
    {
        $type = new PhoneNumberType();
        $form = $this->factory->create($type);

        $form->submit($input);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $this->assertSame($output, $view->vars['value']);
    }

    public function defaultFormattingProvider()
    {
        return array(
            array(array('phone' => '+1-234-234-2345'), array('phone' => '+1-234-234-2345')),
            array(array('phone' => '+441234567890'), array('phone' => '+441234567890')),
        );
    }
}