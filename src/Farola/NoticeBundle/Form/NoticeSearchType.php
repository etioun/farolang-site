<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\NoticeBundle\Entity\Notice;


class NoticeSearchType extends AbstractType
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nh = $this->container->get('farola_notice.helper');
        $searchType = $options['search_type'];

        if ($searchType == 'all')
        {
            $builder
                ->add('categories', 'choice', array(
                    'required' => false,
                    'choices'=> array(
                        Notice::CAT_TEACHING => 'Teachers',
                        Notice::CAT_STUDENT => 'Students', 
                        Notice::CAT_LANG_EX => 'Language partners',
                        Notice::CAT_OTHER => 'Humm, something different !'),
                    'multiple' => true,
                    'expanded' => true,
                    'label' => "I'm looking for :" 
                    ))
                ->add('involvedLanguage', 'farola_language',  array('label'=>'Language involved','required' => false))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Located in',
                    'onlineService_label'=>'Online classes / Service only'));
        } 
        elseif ($searchType == 'learn')
        {
            $builder
                ->add('categories', 'choice', array(
                    'required' => false,
                    'choices'=> array(
                        Notice::CAT_TEACHING => 'Teachers', 
                        Notice::CAT_LANG_EX => 'Language partners'),
                    'multiple' => true,
                    'expanded' => true 
                    ))
                ->add('teachedLanguage', 'farola_language',  array('label'=>'I want to learn','required' => false))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Teacher located in',
                    'onlineService_label'=>'Online classes only'))
                ->add('spokenLanguage', 'farola_language', array('required' => false));

        } elseif ($searchType == 'teach')
        {
            $builder
                ->add('categories', 'choice', array(
                    'required' => false,
                    'choices'=> array(
                        Notice::CAT_STUDENT => 'Students', 
                        Notice::CAT_LANG_EX => 'Language partners'),
                    'multiple' => true,
                    'expanded' => true 
                    ))
                ->add('learnedLanguage', 'farola_language',  array('label'=>'I can teach', 'required' => false))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Teacher located in',
                    'onlineService_label'=>'Online classes only'))
                ->add('spokenLanguage', 'farola_language', array('required' => false));

        }elseif ($searchType == 'teacher_notice') {
            $builder
                ->add('teachedLanguage', 'farola_language',  array('required' => false))
                ->add('spokenLanguage', 'farola_language', array('required' => false))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Teacher located in',
                    'onlineService_label'=>'Online classes'));
        } elseif ($searchType == 'student_notice')  {
            $builder
                ->add('learnedLanguage', 'farola_language',  array('required' => false))
                ->add('spokenLanguage', 'farola_language', array('required' => false))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Student located in',
                    'onlineService_label'=>'Online classes'));
        } elseif ($searchType == 'lang_ex_notice') {
            $builder
                //->add('spokenLanguage', 'farola_language', array('required' => false))
                ->add('teachedLanguage', 'farola_language',  array(
                    'label'=>'I want to practice','required' => false))
                ->add('learnedLanguage', 'farola_language',  array('label'=>'I can help practicing','required' => false)) 
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'profile_country_label'=>'Language partner located in',
                    'onlineService_label'=>'Practice online only'));
        } 
        elseif ($searchType == 'other_notice') {
            $builder
                ->add('category', 'hidden')
                ->add('teachedLanguage', 'farola_language', array(
                    'required'=>false,
                    'label'=> 'Language'))
                ->add('tags', 'farola_tag', array(
                    'choices' => $nh->getTags($nh->getTagScope(Notice::CAT_OTHER))))
                ->add('onlineOrLocation', new OnlineOrLocationSearchType, array(
                    'inherit_data' => true,
                    'label'=> false,
                    'use_profile_country' => false,
                    'onlineService_label'=>'Online services only'));
                
        }

        // $builder->add('search', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'search_type' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_notice_search';
    }
}
