<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class GeneralType extends AbstractType
{
    protected $ph;
    
    public function __construct($ph) {
        $this->ph = $ph;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder->create('name', 'text', array('required' => true))
                    ->addModelTransformer(new Capitalizer)
            )
            ->add('dateOfBirth', 'date', array('widget' => 'choice', 'years' => range(1930,2014)
                , 'format' => 'yyyy-MM-dd',
                'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                'required' => false,
                'invalid_message' => 'The date of birth is invalid',
                'error_bubbling' =>true))
            ->add('gender','choice', array(
                    'empty_value'=>'Gender',
                    'choices' => array('M' => 'Male', 'F'=>'Female'),
                    'required' => false))
            ->add('cof','farola_country', array(
                'empty_value' => 'Country', 
                'required' => false, 
                'property_path'=>'countryOfOrigin'))
            ->add('location', 'farola_location', array('inherit_data'=>true))
            ->add(
                $builder->create('spokenLanguages', 'farola_collection', 
                array( 'type' => new SpokenLanguageType($this->ph),
                       'allow_add' => true,
                       'allow_delete' => true,
                       'prototype' => true,
                       'maxItems' => 6))->addModelTransformer(new SpokenLanguagesCollTransformer)
                );
            // ->add('update1','submit');
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
        return 'farola_profilebundle_profile';
    }
}
