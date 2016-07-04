<?php

namespace Farola\CurrencyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;   

class CurrencyType extends AbstractType
{
    protected $currAdapt;

    protected $filteredCurrencies;

    protected function getFilteredCurrencies() {
        if (empty($this->filteredCurrencies)) {
            $curr = Intl::getCurrencyBundle()->getCurrencyNames();
            $this->filteredCurrencies = array_intersect_key(
                $curr, 
                array_flip($this->currAdapt->getManagedCurrencies()
            ));
        }
        return $this->filteredCurrencies;
    }

    public function __construct($currAdapt){
        $this->currAdapt = $currAdapt;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->getFilteredCurrencies()
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_currencybundle_currency';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'farola_filterable_select';
    }
}
