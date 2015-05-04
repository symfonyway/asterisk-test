<?php

namespace AsteriskTest\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm('phone_number_type');

        $form->handleRequest($request);
        if ($form->isValid()) {

        }

        return $this->render('AsteriskTestAppBundle:Default:index.html.twig', array(
                'form' => $form->createView()
            ));
    }
}
