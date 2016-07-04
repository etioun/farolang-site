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
use Farola\MainBundle\Form\Common\CountryType;



class OnlineOrLocationSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('onlineService', 'checkbox', array(
                'required'=>false,
                'label' => $options['onlineService_label']))           
            ->add('location', 'farola_location', array(
                'inherit_data' => true,
                'label' => 'Near (10km around)'));           
            if ($options['use_profile_country'])
            {
                $builder
                    ->add('profile_country', 'farola_country', array(
                        'label' => $options['profile_country_label'], 
                        'placeholder'=>'Enter any country',
                        'required'=>false));
            }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setRequired(array(
            'use_profile_country', 
            'onlineService_label'
        ));

       $resolver->setDefaults(array(
            'use_profile_country' => true,
            'profile_country_label'=> 'Country', 
            'onlineService_label' => 'Online'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_notice_onlineOrLocation_search';
    }
}
