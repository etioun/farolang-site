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

class CountryType extends AbstractType
{

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => Intl::getRegionBundle()->getCountryNames(),
            'placeholder'=> 'Enter whatever country'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_country';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'farola_filterable_select';
    }
}
