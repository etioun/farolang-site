<?php

namespace Farola\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Farola\ProfileBundle\Form\Capitalizer;
use Farola\UserBundle\Form\NativeLanguageSetter;

class GetStartedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add(
                $builder->create('name', 'text', array('label'=>'Name or nickname','required' => true))
                    ->addModelTransformer(new Capitalizer))
            ->add(
                $builder->create('spokenLanguages','farola_language', array('label'=>'Native language','required' => true))
                    ->addModelTransformer(new NativeLanguageSetter))
            ->add('aboutMe','textarea', array('attr'=>array(
                'rows'=>5,
                'placeholder'=>'Say a little something about you...')
                    ,'required' => true));

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
        return 'farola_profilebundle_user_getStarted';
    }
}
