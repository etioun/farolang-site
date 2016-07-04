<?php

namespace Farola\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixLanguagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frl:fix:languages')
            ->setDescription('Ajout de - - sur les codes de langues')
            ->addOption(
               'update',
               null,
               InputOption::VALUE_NONE,
               'If defined changes will be really perfomed'
            )
        ;
    }

    protected function majSpokenLanguages(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $filters = $em->getFilters();
        if ($filters->isEnabled('softdeleteable'))
        {
           $em->getFilters()->disable('softdeleteable'); 
        }
        $profiles = $em->getRepository('FarolaProfileBundle:Profile')->findAll();

        foreach ($profiles as $profile) {
            $spokenLanguages = $profile->getSpokenLanguages();
        
            if ($spokenLanguages == null || empty($spokenLanguages))
                continue;

            $newArray = [];

            foreach ($spokenLanguages as $languageLvlStr)
            {
                if(strstr($languageLvlStr,'-'))
                {
                    $newArray[] = $languageLvlStr;
                }
                else
                {
                    list($language, $lvl) = preg_split('/:/', $languageLvlStr, 2);
                    $newArray[] = '-'.$language.'-:'.$lvl;
                }

            }
            if ($newArray != $spokenLanguages)
            {
                $output->writeln('profile '.$profile->getId().' will be modified');
                $output->writeln('old value :'.implode(',',$spokenLanguages));
                $output->writeln('new value :'.implode(',',$newArray));
                $profile->setSpokenLanguages($newArray);
            }

        }
        if ($input->getOption('update')) {
            
            $output->writeln('Mise a jour en cours');
            $em->flush();        
            $output->writeln('Mise a jour effectuee');
            $em->clear();
        }
    }

    protected function majTeachedLanguage($notice, InputInterface $input, OutputInterface $output)
    {
        $teachedLanguages = $notice->getTeachedLanguage();
        
        if ($teachedLanguages == null || empty($teachedLanguages))
            return;

        $newArray = [];

    

        foreach ($teachedLanguages as $language)
        {
            if(strstr($language,'-'))
            {
                $newArray[] = $language;
            }
            else
            {
                $newArray[] = '-'.$language.'-';
            }

        }
        if ($newArray != $teachedLanguages)
        {
            $output->writeln('teachedlanguage');
            $output->writeln('notice '.$notice->getId().' will be modified');
            $output->writeln('old value :'.implode(',',$teachedLanguages));
            $output->writeln('new value :'.implode(',',$newArray));
            $notice->setTeachedLanguage($newArray);
        }
    }

    protected function majLearnedLanguage($notice, InputInterface $input, OutputInterface $output)
    {
        $learned = $notice->getLearnedLanguage();

        if ($learned == null)
            return;
            
        if(strstr($learned,'-'))
        {
            $new = $learned;
        }
        else
        {
            $new = '-'.$learned.'-';
        }

        if ($new != $learned)
        {
            $output->writeln('learnedlanguage');
            $output->writeln('notice '.$notice->getId().' will be modified');
            $output->writeln('old value :'.$learned);
            $output->writeln('new value :'.$new);
            $notice->setLearnedLanguage($new);
        }
    }

    protected function majNotices(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $filters = $em->getFilters();
        if ($filters->isEnabled('softdeleteable'))
        {
           $em->getFilters()->disable('softdeleteable'); 
        }
        
        $notices = $em->getRepository('FarolaNoticeBundle:Notice')->findAll();

        foreach ($notices as $notice) {
            
            $this->majTeachedLanguage($notice, $input, $output);
            $this->majLearnedLanguage($notice, $input, $output);
        }


        if ($input->getOption('update')) {
            $output->writeln('let s update !');
            
            $output->writeln('Mise a jour en cours');
            $em->flush();        
            $output->writeln('Mise a jour effectuee');
            $em->clear();
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('maj spokenLanguages');
        $this->majSpokenLanguages($input, $output);

        $output->writeln('maj notices');
        $this->majNotices($input, $output);

        // $output->writeln('maj user');
        // $this->majUser($input, $output);

        
        
    }
}