<?php

namespace AsteriskTest\AppBundle\Form\Type;

use AsteriskTest\AppBundle\Validator\Constraints\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PhoneNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array(
                    'label' => false,
                    'constraints' => array(
                        new NotBlank(),
                        new PhoneNumber()
                    )
                ))
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'phone_number';
    }
}