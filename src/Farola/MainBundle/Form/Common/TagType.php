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

class TagType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
       $allTags = $form->getConfig()->getOptions()['choices'];
       $view->vars['dataTags'] = [];
       if (null !== $form->getData())
       {
           foreach ($form->getData() as $tag) {
               $tagName = $allTags[$tag];
               $view->vars['dataTags'][$tag] = $tagName;
           }
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'expanded' => true, 
            'multiple' => true));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_tag';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }
}
