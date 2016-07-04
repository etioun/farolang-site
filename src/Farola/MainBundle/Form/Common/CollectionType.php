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

class CollectionType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
       $view->vars['addBtn_label'] = $options['addBtn_label'];
       $view->vars['maxItems'] = $options['maxItems'];
       $view->vars['addBtn_label_max'] = $options['addBtn_label_max'];
    }

     /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'allow_add' => true,
          'allow_delete' => true,
          'prototype' => true,
          'addBtn_label' => 'Add',
          'addBtn_label_max' => 'Add (Cannot add more)',
          'maxItems'=> 2
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_collection';
    }


    /**
     * @return string
     */
    public function getParent()
    {
        return 'collection';
    }
}
