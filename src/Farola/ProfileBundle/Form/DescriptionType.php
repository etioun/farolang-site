<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DescriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aboutMe', 'textarea', 
                array('required' => false))
            // ->add(
            //   $builder->create('personalVideoLink', 'text', 
            //     array( 'required' => false))->addModelTransformer(new UrlTransformer))

            ->add('experience', 'textarea', 
                array('required' => false))
            ->add('interests', 'textarea', 
                array('required' => false))
            ->add('weblinks', 'farola_collection', 
                array( 'type' => new WeblinkType,
                       'allow_add' => true,
                       'allow_delete' => true,
                       'prototype' => true,
                       'required' => false,
                       'maxItems' => 10));
            // ->add('update1','submit')
            // ->add('update2','submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Farola\ProfileBundle\Entity\Profile'
        ));
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_profilebundle_profile_description';
    }
}
