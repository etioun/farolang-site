<?php

namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Farola\ProfileBundle\RefData\LanguageLevelRefData;

class WeblinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'choice', array(
                'choices' => $this->getCategChoices()
                ,'required' => true))
            ->add(
                $builder->create('link', 'text', array('required' => true))
                    ->addModelTransformer(new UrlTransformer));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // $resolver->setDefaults(array(
        //     'data_class' => 'Farola\ProfileBundle\Entity\SpokenLanguage'
        // ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_profilebundle_weblink';
    }

    private function getCategChoices()
    {
        $return = [];
        $return['Blog']='Blog';
        $return['Couchsurfing']='Couchsurfing';
        $return['Facebook']='Facebook';
        $return['Linkedin']='Linkedin';
        $return['Other']='Other';
        $return['Viadeo']='Viadeo';
        $return['Twitter']='Twitter';
        $return['Personal video']='Personal video';
        $return['Personal website']='Personal website';

        return $return;

    }
}
