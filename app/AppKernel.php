<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            //new FOS\MessageBundle\FOSMessageBundle(),
            //new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            //new JMS\TranslationBundle\JMSTranslationBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Lexik\Bundle\CurrencyBundle\LexikCurrencyBundle(),

            new Farola\UserBundle\FarolaUserBundle(),
            new Farola\MessageBundle\FarolaMessageBundle(),
            new Farola\ProfileBundle\FarolaProfileBundle(),
            new Farola\NoticeBundle\FarolaNoticeBundle(),
            new Farola\CurrencyBundle\FarolaCurrencyBundle(),
            new Farola\HomeBundle\FarolaHomeBundle(),
            new Farola\MainBundle\FarolaMainBundle(),
            new Farola\TestBundle\FarolaTestBundle(),
            new Farola\NotificationBundle\FarolaNotificationBundle(),
        );

        if (in_array($this->getEnvironment(), array( 'dev'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
        }
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            // $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function __construct($environment, $debug)
    {
        date_default_timezone_set( 'Europe/Paris' );
        parent::__construct($environment, $debug);
    }
}
