<?php

namespace Farola\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class PreferenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('prefLocale', 'language', array('required' => true))
            ->add('prefTimezone', 'farola_timezone', array('label'=>'Preferred timezone','required' => true))
            ->add('prefCurrency','farola_currencybundle_currency', array('label'=>'Preferred currency','required' => true))
            ->add('acceptNotification','checkbox', array('label'=>'Receive mail notifications'))
            ->add('udpate','submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Farola\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_profilebundle_user_preference';
    }
}
