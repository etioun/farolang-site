<?php

namespace Farola\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class FeedbackType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('category', 'choice', array(
                'label'=>'Reason',
                'required' => true,
                'choices' => json_decode(file_get_contents(__DIR__.'/../../Resources/ref_data/feedback-categories.json'), true)
                ))
            ->add('summary','text', array('label'=>'Summary','required' => true))
            ->add('detail','textarea', array('attr'=>array(
                'rows'=>5,
                'placeholder'=>'Message details')
                    ,'required' => true));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Farola\UserBundle\Entity\Feedback'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_userbundle_feedback';
    }
}
