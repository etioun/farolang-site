<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Farola\NoticeBundle\Entity\Notice;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;


class OnlineOrLocationType extends AbstractType
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['category'] == Notice::CAT_TEACHING)
        {
             $view->vars['onlineCheckerLabel']= array(
                    'online' => 'I want to give online classes',
                    'location' => 'I want to teach near a specific location (10km around)');           
        }
        elseif ($options['category'] == Notice::CAT_STUDENT)
        {
            $view->vars['onlineCheckerLabel']= array(
                    'online' => 'I want to learn online',
                    'location' => 'I want to learn near a specific location (10km around)');    
        }
        elseif ($options['category'] == Notice::CAT_LANG_EX)
        {
            $view->vars['onlineCheckerLabel']= array(
                    'online' => 'I want to pratice online',
                    'location' => 'I want to practice near a specific location (10km around)');    
        }
        elseif ($options['category'] == Notice::CAT_OTHER)
        {
            $view->vars['onlineCheckerLabel']= array(
                    'online' => "Is it about something online/remote ?",
                    'location' => 'Is it about something near a specific location ?');    
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nh = $this->container->get('farola_notice.helper');
        
        $builder
            ->add('nearLocation', 'checkbox', array('required'=>false))
            ->add('onlineService', 'checkbox', array('required'=>false))
            ->add('location', 'farola_location', array('label'=>false, 'inherit_data' => true));
            if($options['category'] != Notice::CAT_OTHER)
                $builder->add('onlineMethod', 'choice', array('label'=>false, 'choices'   => $nh->getOnlineMethRef()));
            // $builder->add('onlineServiceSelector', 'choice', array(
            //     'choices'   => array(
            //         'true'   => 'I want to teach online', 
            //         'false' => 'I want to teach near my location'),
            //     'expanded' => true,
            //     'mapped' => false));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setRequired(array(
            'category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_notice_onlineOrLocation';
    }
}
