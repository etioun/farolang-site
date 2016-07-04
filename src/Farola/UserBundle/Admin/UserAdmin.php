<?php

namespace Farola\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('username')
            ->add('usernameCanonical')
            //->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            //->add('salt')
            //->add('password')
            //->add('lastLogin')
            ->add('locked')
            ->add('expired')
            //->add('expiresAt')
            //->add('confirmationToken')
            //->add('passwordRequestedAt')
            // ->add('roles')
            // ->add('credentialsExpired')
            // ->add('credentialsExpireAt')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            //->add('username')
            ->add('usernameCanonical')
            //->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            //->add('salt')
            //->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            // ->add('expiresAt')
            // ->add('confirmationToken')
            // ->add('passwordRequestedAt')
            // ->add('roles')
            // ->add('credentialsExpired')
            // ->add('credentialsExpireAt')
            ->add('id')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('username')
            ->add('usernameCanonical')
            // ->add('email')
            ->add('emailCanonical')
            ->add('enabled', null, array('required' => false))
            // ->add('salt')
            // ->add('password')
            ->add('lastLogin', null, array('required' => false))
            ->add('locked', null, array('required' => false))
            ->add('expired', null, array('required' => false))
            // ->add('expiresAt')
            // ->add('confirmationToken')
            ->add('passwordRequestedAt', null, array('required' => false))
            ->add('roles', 'choice', array(
            'choices' => array(
                'ROLE_ADMIN' => 'Admin',
                'ROLE_USER' => 'User'
            ),
            'expanded' => false,
            'multiple' => true,
            'required' => false
        ))
            ->add('credentialsExpired', null, array('required' => false))
            // ->add('credentialsExpireAt')
            // ->add('id')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            // ->add('username')
            ->add('usernameCanonical')
            // ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            // ->add('salt')
            // ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            // ->add('expiresAt')
            // ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
        ;
    }
}
