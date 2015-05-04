<?php

namespace AsteriskTest\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('phone', 'phone_number', array(

                ))
            ->add('submit', 'submit', array(

                ))
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $www = 1;
        } else {
            $www = 0;
        }

        return $this->render('AsteriskTestAppBundle:Default:index.html.twig', array(
                'form' => $form->createView()
            ));
    }
}
