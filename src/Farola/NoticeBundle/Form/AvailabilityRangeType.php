<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Farola\ProfileBundle\RefData\LanguageLevelRefData;

class AvailabilityRangeType extends AbstractType
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nh = $this->container->get('farola_notice.helper');
        $builder
            ->add('dayOfWeek', 'choice', array(
                'choices' => $nh->getDaysOfWeek()['names']))
            ->add('period', 'choice', array(
                'choices' => $nh->getPeriods()['names']));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_noticebundle_availabilityrange';
    }
}
