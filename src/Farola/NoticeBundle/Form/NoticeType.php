<?php

namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Intl\Intl;
use Farola\MainBundle\Form\Common\LocationType;
use Farola\NoticeBundle\Entity\Notice;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;   

class NoticeType extends AbstractType
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['category']= $options['category'];    
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activated', 'checkbox', array('required'=>false));
        if ($options['category'] == Notice::CAT_TEACHING) {
            $this->buildTeachForm($builder, $options);
        }
        else if ($options['category'] == Notice::CAT_STUDENT) {
            $this->buildStudentForm($builder, $options);   
        }
        else if ($options['category'] == Notice::CAT_LANG_EX) {
            $this->buildLangExForm($builder, $options);   
        }
        else if ($options['category'] == Notice::CAT_OTHER) {
            $this->buildOtherForm($builder, $options);   
        }
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    protected function buildTeachForm($builder, $options) {
        
        $nh = $this->container->get('farola_notice.helper');
        
        $builder
                ->add('onlineOrLocation', 
                    new OnlineOrLocationType($this->container), 
                    array('label'=>false, 'inherit_data' => true, 'category'=>$options['category']));
        // $builder
        //         ->add('onlineMethod', 'choice', array('choices'   => $nh->getOnlineMethRef()))
        //         ->add('location', 'farola_location', array('inherit_data' => true));
        
        $builder
            ->add(
                $builder->create('teachedLanguage', 'farola_language')
                    ->addModelTransformer(new ArrayWrapTransformer())
                )
            ->add('description', 'textarea', array('attr'=>array('rows'=>5, 'placeholder'=>'Tell a bit more about what your need or what you can offer')))
            // ->add('tags', 'farola_tag', array(
            //         'choices' => $nh->getTags($nh->getTagScope($options['category']))))
            ->add('rate', new RateType, array('label'=>false, 'inherit_data'=>true, 'price_label'=>'My hourly rate'))
            // ->add('localPrice','integer', array('label'=> 'My hourly rate', 'attr'=>array('min'=>0, 'max'=>30000))) 
            // ->add('localCurrency', 'farola_currencybundle_currency', array('label'=>'Currency'))
            ->add('availability', new AvailabilityType($this->container), array('inherit_data' => true));
    }

    protected function buildStudentForm($builder,$options) {
        $nh = $this->container->get('farola_notice.helper');
        
        $builder
            ->add('onlineOrLocation', 
                new OnlineOrLocationType($this->container), 
                array('label'=>false,'inherit_data' => true, 'category'=>$options['category']));
        // $builder
        //         ->add('onlineMethod', 'choice', array('choices'   => $nh->getOnlineMethRef()))
        //         ->add('location', 'farola_location', array('inherit_data' => true));
        $builder
            ->add('learnedLanguage', 'farola_language')
            ->add('description', 'textarea', array('required'=>true,'attr'=>array('rows'=>5, 'placeholder'=>'Tell a bit more about what your need or what you can offer')))
            // ->add('tags', 'farola_tag', array(
            //         'choices' => $nh->getTags($nh->getTagScope($options['category']))))
            ->add('rate', new RateType, array('label'=>false, 'inherit_data'=>true,'price_label'=> 'Approximative hourly rate i can pay'))
            
            // ->add('localPrice','integer', array('label'=> 'Approximative hourly rate i would accept to pay', 'attr'=>array('min'=>0, 'max'=>30000))) 
            // ->add('localCurrency', 'farola_currencybundle_currency', array('label'=>'Currency'))
            ->add('availability', new AvailabilityType($this->container), array('inherit_data' => true));
        
    }

    protected function buildLangExForm($builder, $options) {
        $nh = $this->container->get('farola_notice.helper');

        $builder
                ->add('onlineOrLocation', 
                    new OnlineOrLocationType($this->container), 
                    array('label'=>false,'inherit_data' => true, 'category'=>$options['category']));
        // $builder
        //         ->add('onlineMethod', 'choice', array('choices'   => $nh->getOnlineMethRef()))
        //         ->add('location', 'farola_location', array('inherit_data' => true));
        $builder
            // ->add('teachedLanguage', 'farola_language', array('label'=>'Language i can teach'))
            ->add('learnedLanguage', 'farola_language', array('label'=>'Language i want to practice'))
            ->add('description', 'textarea', array('attr'=>array('rows'=>5,'placeholder'=>'Tell a bit more about what your need or what you can offer')))
            ->add('availability', new AvailabilityType($this->container), array('inherit_data' => true));
    }

    protected function buildOtherForm($builder, $options) {
        $nh = $this->container->get('farola_notice.helper');

        $builder
            ->add('onlineOrLocation', 
                new OnlineOrLocationType($this->container), 
                array('label'=>false,'inherit_data' => true, 'category'=>$options['category']));
        $builder
                ->add(
                   $builder->create('teachedLanguage', 'farola_language', array(
                    'label'=>'Language involved',
                    'required' =>false,
                    'placeholder' =>'Enter a language or leave it blank'
                    ))->addModelTransformer(new ArrayWrapTransformer())
                );
        $builder
            ->add('tags', 'farola_tag', array(
                    'choices' => $nh->getTags($nh->getTagScope(Notice::CAT_OTHER))))
            ->add('description', 'textarea', array('attr'=>array('rows'=>5,'placeholder'=>'Tell a bit more about what your need or what you can offer')));
    }

    public function onPreSetData(FormEvent $event){
        $notice = $event->getData();
        $form = $event->getForm();
            
        if ($notice->getId())
        {
            // $form->remove('onlineOrLocation');
            $form->remove('create');

            // if ($notice->getOnlineService())
            // {
            //     $form->remove('location');
            // }
            // else
            // {
            //      $form->remove('onlineMethod');
            // }
        }
        else
        {
            $form->remove('activated');
            $form->remove('location');
            $form->remove('onlineMethod');
            $form->remove('update');
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Farola\NoticeBundle\Entity\Notice'));

        $resolver->setRequired(array(
            'category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'farola_noticebundle_notice';
    }
}
