<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Farola\ProfileBundle\RefData\LanguageLevelRefData;

class SpokenLanguageType extends AbstractType
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
            ->add('language', 'farola_language', array('placeholder' => 'Language'))
            ->add('level', 'choice', array(
                    'choices' => $this->ph->getLanguageLevelsRef(),
                    'placeholder' => 'Level'));
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
        return 'farola_profilebundle_spokenlanguage';
    }
}
