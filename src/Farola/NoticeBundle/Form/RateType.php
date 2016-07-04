<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;

class RateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localPrice','integer', array('label'=> $options['price_label'], 'attr'=>array('min'=>0, 'max'=>30000))) 
            ->add('localCurrency', 'farola_currencybundle_currency', array('label'=>'Currency'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults(array(
            'price_label'=>"Rate"
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_noticebundle_rate';
    }
}
