<?php

namespace AsteriskTest\AppBundle\Controller;

use AsteriskTest\AppBundle\Model\PhoneNumber;
use phparia\Events\Event;
use phparia\Events\StasisStart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use phparia\Client\Client;

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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function watchAction()
    {
        return $this->render('@AsteriskTestApp/Default/watch.html.twig', array(

            ));
    }

    public function listenAction()
    {
        $phoneNumber = new PhoneNumber();
        $client = new Client('asterisk', 'asterisk', 'hello-world', '192.168.2.222', 8088);

        $client->getStasisClient()->on(Event::STASIS_START, function(StasisStart $event) use ($client, $phoneNumber) {
                /** @var Channel $channel */
                $channel = $event->getChannel();

                $channel->playMedia('sound:demo-thanks');

                $channel->onChannelDtmfReceived(function($event) use ($channel, $phoneNumber) {
                        /** @var Channel $channel */

                        $digit = $event->getDigit();
                        if ($digit === '#') {
                            $channel->answer($channel->getId());
                            $channel->deleteChannel();
                        } else {
                            $phoneNumber->addSymbolToPhoneNumber($digit);
                        }
                    });
            });

        $client->getStasisClient()->on(Event::CHANNEL_HANGUP_REQUEST, function($event) use ($client, $phoneNumber) {
                $client->getStasisClient()->close();

            });

        $client->run();

        $validator = $this->get('validator');
        $errorList = $validator->validate($phoneNumber);

        $errors = array();
        if (count($errorList)) {
            foreach ($errorList as $error) {
                /** @var ConstraintViolation $error */

                $message = $error->getMessage();
                $errors[] = $message;
            }
        }

        return new JsonResponse(array(
                'phoneNumber' => $phoneNumber->getPhoneNumber(),
                'errors' => $errors
            ));
    }
}
