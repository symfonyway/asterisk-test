<?php

namespace AsteriskTest\AppBundle\Command;

use phparia\Events\StasisStart;
use phparia\Resources\Channel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AriCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('asterisk:ari')
            ->setDescription('Test ARI')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start command');

        $client = new \phparia\Client\Client('asterisk', 'asterisk', 'hello-world', '192.168.2.222', 8088);

        $client->getStasisClient()->on(\phparia\Events\Event::STASIS_START, function(StasisStart $event) use ($client) {
                /** @var Channel $channel */
                $rrr = 3;
//                $channel = $event->getChannel();
//
                $logger = $client->getLogger();
//                $logger->notice('Notice!!');
//
//                $channel->startRinging();
//                $channel->playMedia('sound:demo-thanks');
//                $channel->answer($channel->getId());
//                $channel->deleteChannel();


                // Put the new channel in a bridge
                $channel = $event->getChannel();
                $channel->playMedia('sound:demo-thanks');

                $bridge = $client->bridges()->createBridge(uniqid(), 'dtmf_events, mixing', 'bridgename');
                $client->bridges()->addChannel($bridge->getId(), $channel->getId(), null);

                // Listen for DTMF and hangup when '#' is pressed
                $channel->onChannelDtmfReceived(function($event) use ($channel, $logger) {
                        /** @var Channel $channel */

                        $logger->notice("Got digit: {$event->getDigit()}");
                        if ($event->getDigit() === '#') {
                            $channel->answer($channel->getId());
                            $channel->deleteChannel();
//                            $channel->hangup();
                        }
                    });
            });

        $client->getStasisClient()->on(\phparia\Events\Event::CHANNEL_HANGUP_REQUEST, function($event) use ($client) {
                $client->getStasisClient()->close();
//                $channel = $event->getChannel();
//                $bridge = $client->bridges()->createBridge(uniqid(), 'dtmf_events, mixing', 'bridgename');
//                $this->client->bridges()->addChannel($bridge->getId(), $channel->getId(), null);
            });

        $output->writeln('run.......................');
        $client->run();

        $output->writeln('End command');
    }
}