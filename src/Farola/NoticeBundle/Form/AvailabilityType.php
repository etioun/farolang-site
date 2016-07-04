<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;

class AvailabilityType extends AbstractType
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
        $builder
            ->add('availableAnytime', 'checkbox', array('required'=>false))
            ->add('timezone', 'farola_timezone')
            ->add('availabilities', 'farola_collection',array( 
                       'type' => new AvailabilityRangeType($this->container),
                       'allow_add' => true,
                       'allow_delete' => true,
                       'prototype' => true,
                       'options' => array(),
                       'maxItems' => 21));
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
        return 'farola_noticebundle_availability';
    }
}
