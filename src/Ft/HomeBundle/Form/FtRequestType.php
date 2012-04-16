<?php

namespace Ft\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FtRequestType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('url')
            ->add('ip')
            ->add('environment')
            ->add('notes')
            ->add('updates_req')
            ->add('moretests_req')
            ->add('type')
            ->add('created')
            ->add('updated')
            ->add('delivered')
        ;
    }

    public function getName()
    {
        return 'ft_homebundle_ftrequesttype';
    }
}
