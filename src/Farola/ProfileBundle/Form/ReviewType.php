<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('review', 'textarea')
            ->add('rating', 'choice', array(
                'choices' => array(0 => 'No rating', 1 => 'Good', 2 => 'Very good', 3 => 'Excellent')));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Farola\ProfileBundle\Entity\Review'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_profilebundle_review';
    }
}
