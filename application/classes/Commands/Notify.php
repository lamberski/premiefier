<?php

namespace Premiefier\Commands;

use Knp\Command\Command;
use Premiefier\Models\Notification;
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
        $application = $this->getSilexApplication();
        $application['capsule']->bootEloquent();

        $notifications = Notification::whereHas('premiere', function ($query) {
            $query->where('released_at', '=', date('Y-m-d', strtotime('+3 days')));
        })->with(['user', 'premiere'])->get();

        foreach ($notifications as $notification) {
            $premiere = $notification->premiere;
            $user     = $notification->user;
            $view     = $application['twig']->render('emails/notification.twig', ['premiere' => $premiere]);
            $message  = \Swift_Message::newInstance()
                ->setFrom([$application['config.mail_from'] => 'Premiefier'])
                ->setTo([$user->email])
                ->setSubject(sprintf('Premire of %s is in 3 days!', $premiere->title))
                ->setBody($view);

            $application['mailer']->send($message);
            $output->writeln(sprintf('Message sent to %s', $user->email));
        }
    }
}
