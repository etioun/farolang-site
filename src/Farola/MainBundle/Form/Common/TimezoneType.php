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

class TimezoneType extends AbstractType
{
    protected $timezones;

    protected  function getTimezones()
    {
        if (empty($this->timezones)) {
            $this->timezones = array();

            foreach (\DateTimeZone::listIdentifiers() as $timezone) {
                
                $this->timezones[$timezone] = $timezone;
            }
        }
        return $this->timezones;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->getTimezones(),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_timezone';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'farola_filterable_select';
    }
}
