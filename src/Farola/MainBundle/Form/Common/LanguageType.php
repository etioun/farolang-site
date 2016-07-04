<?php

namespace Farola\MainBundle\Form\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;   

class LanguageType extends AbstractType
{

    protected $rdh;

    public function __construct($rdh){
        $this->rdh = $rdh;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->rdh->getLanguages(),
            'placeholder' => "Enter whatever language"
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_language';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'farola_filterable_select';
    }
}
