<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;

class ProfileSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
            ->add('spokenLanguage', 'farola_language', array('required' => false))
            ->add('country', 'farola_country', array('label'=>'Located in','required' => false))
            ->add('location', 'farola_location', array('label'=>'Nearby','inherit_data' => true));
            // ->add('reviews', 'choice', array(
            //     'required'=>false,
            //     'placeholder'=> 'Nb of reviews received',
            //     'choices'=>array(
            //         '2' => 'More than 2 reviews',
            //         '10' => 'More than 10 reviews',
            //         '20' => 'More than 20 reviews')));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // $resolver->setDefaults(array(
        //     'data_class' => '\ArrayObject'
        // ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_profilebundle_search';
    }
}
