<?php

namespace Premiefier\Commands;

use Silex\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\Command\Command;
use Premiefier\Models\Notification;

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
