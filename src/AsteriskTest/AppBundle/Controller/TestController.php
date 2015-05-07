<?php

namespace AsteriskTest\AppBundle\Controller;

use phparia\Resources\Channel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function phpariaAction()
    {
        $this->client = new \phparia\Client\Client('ari', 'aritest27', 'test3', '104.131.20.230', 8080);
        $this->client->getStasisClient()->on(\phparia\Events\Event::STASIS_START, function($event) {
                $channel = $event->getChannel();
                $bridge = $this->client->bridges()->createBridge(uniqid(), 'dtmf_events, mixing', 'bridgename');
                $this->client->bridges()->addChannel($bridge->getId(), $channel->getId(), null);
            });

        $this->client->getStasisClient()->on(\phparia\Events\Event::CHANNEL_HANGUP_REQUEST, function($event) {
                /** @var Channel $channel */
                $channel = $event->getChannel();
                $channel->answer($channel->getId());
                $channel->playMedia('beep');
                $channel->deleteChannel();
            });

        $this->client->run();

        return new Response('1234');
    }
}