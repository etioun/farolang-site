<?php

namespace Farola\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ActivityMailNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frl:mail-notification:activity')
            ->setDescription('Send a mail to users which had an activity on there account')
            ->addOption(
               'all',
               null,
               InputOption::VALUE_NONE,
               'do it for all users'
            )
            ->addOption(
               'test',
               null,
               InputOption::VALUE_NONE,
               'do it for Etioun, Etioun2'
            )
            ->addOption(
               'sendDevMail',
               null,
               InputOption::VALUE_NONE,
               'send an email to say it s done'
            )
        ;
    }

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('all') == false
            && $input->getOption('test') == false)
            return ;


        $output->writeln('get active users who got unread stuff since last email');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $twig = $this->getContainer()->get('twig');
        $mailer = $this->getContainer()->get('mailer');
        $users = $em->getRepository('FarolaUserBundle:User')->getUnnotifiedUsersWithUnreadMessages();
        $mailBody = "";

        foreach ($users as $user) {
            if ($input->getOption('test') 
                && $user->getUserName() != 'Etioun'
                && $user->getUserName() != 'Kikooman'
                && $user->getUserName() != 'Etioun2')
            {
                continue;
            }
            $output->writeln('User '.$user->getUserName());
            $mailBody = $mailBody." ".$user->getUserName();

            $template = $twig->loadTemplate("FarolaNotificationBundle:ActivityNotification:email.txt.twig");
            $subject = $template->renderBlock('subject', array());
            $textBody = $template->renderBlock('body_text',array('name'=>$user->getUserName()));
            $htmlBody = $template->renderBlock('body_html', array('name'=>$user->getUserName()));

            //send message
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array("noreply@farolang.com" => "Farolang"))
                ->setTo($user->getEmail());

            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');

           $mailer->send($message);


            //set notification date
           $user->setLastMailNotificationAt(new \DateTime);
        }
        //flush
        $em->flush();
        $output->writeln('finished');

        if ($input->getOption('sendDevMail')) {
            if (empty($mailBody)==false)
            {
              $message = \Swift_Message::newInstance()
                  ->setSubject('task send notif')
                  ->setFrom(array("noreply@farola.etienne.odns.fr" => "Farola dev"))
                  ->setTo('maiskikicest@gmail.com');

              $message->setBody($mailBody, 'text/html');
             $mailer->send($message);
            }
        }

    }

}