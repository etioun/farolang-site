<?php

namespace Farola\MainBundle\Form\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', 'text', array('attr' => array(
                'placeholder' => 'Enter whatever location',
            ), 'required'=>false))
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden')
            ->add('country', 'hidden')
            ->add('placeId', 'hidden');
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
        return 'farola_location';
    }
}
