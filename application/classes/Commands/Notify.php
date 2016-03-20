<?php

namespace Premiefier\Commands;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Notify extends Command
{
    protected function configure()
    {
        $this
            ->setName('notify')
            ->setDescription('Sends email notifications to all subscribed users.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();
        $output->writeln('Test');
    }
}
