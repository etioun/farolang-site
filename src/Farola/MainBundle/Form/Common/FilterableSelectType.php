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

class FilterableSelectType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
       $allData = $form->getConfig()->getOptions()['choices'];
       $view->vars['selectedDataName'] = '';
       if (null != $form->getData())
       {

           if (isset($allData[$form->getNormData()]) == false) {
            $view->vars['selectedDataName'] = $form->getData();
           }
           else
           {
            $view->vars['selectedDataName'] = $allData[$form->getNormData()];
           }
        }
        $view->vars['dataSource'] = $options['choices'];
    }

     /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'placeholder' => "Start typing...",
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_filterable_select';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }
}
