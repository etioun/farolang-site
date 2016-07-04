<?php

namespace Farola\NoticeBundle\Form;

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
    protected $nh;

    protected $tagScope;

    public function __construct($nh, $tagScope) {
        $this->tagScope = $tagScope;
        $this->nh = $nh;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tagScope']= $this->tagScope;    
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        dump($this->nh->getTags($this->tagScope));

        $resolver->setDefaults(array(
            'choices' => array(
                $this->nh->getTags($this->tagScope)
            ),
            'expanded' => true, 
            'multiple' => true));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_noticebundle_tag';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }
}
