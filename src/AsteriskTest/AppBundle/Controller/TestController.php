<?php

namespace AsteriskTest\AppBundle\Controller;

use phparia\Resources\Channel;
use phparia\Events\StasisStart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function phpariaAction()
    {
        $this->client = new \phparia\Client\Client('asterisk', 'asterisk', 'hello-world', '192.168.2.222', 8088);

        $this->client->getStasisClient()->on(\phparia\Events\Event::STASIS_START, function(StasisStart $event) {
                /** @var Channel $channel */
                $channel = $event->getChannel();
                $channel->startRinging();
                $channel->playMedia('sound:goodbye');
                $channel->answer($channel->getId());
                $channel->deleteChannel();
            });

        $this->client->getStasisClient()->on(\phparia\Events\Event::CHANNEL_HANGUP_REQUEST, function($event) {
                $channel = $event->getChannel();
                $bridge = $this->client->bridges()->createBridge(uniqid(), 'dtmf_events, mixing', 'bridgename');
                $this->client->bridges()->addChannel($bridge->getId(), $channel->getId(), null);
            });

        $this->client->run();

        return new Response('1234');
    }
}