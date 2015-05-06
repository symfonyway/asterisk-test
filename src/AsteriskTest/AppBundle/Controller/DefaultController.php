<?php

namespace AsteriskTest\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->add('phone', 'phone_number')
            ->add('submit', 'submit')
            ->getForm()
        ;

        if ($request->isXmlHttpRequest()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                //TODO: call

                return new JsonResponse(array(
                        'status' => 'ok',
                        'message' => 'Phone number has been submitted.'
                    ));
            } else {
                //TODO: reject

                return new JsonResponse(array(
                        'status' => 'error',
                        'message' => $form->getErrors(true, false)->__toString()
                    ));
            }
        }

        return $this->render('AsteriskTestAppBundle:Default:index.html.twig', array(
                'form' => $form->createView()
            ));
    }
}
