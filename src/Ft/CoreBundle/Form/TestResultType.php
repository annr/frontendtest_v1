<?php

namespace Ft\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TestResultType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('ft_request_id')
            ->add('kind')
            ->add('class_name')
            ->add('version')
            ->add('heading')
            ->add('body')
            ->add('notes')
            ->add('weight')
            ->add('ft_request')
        ;
    }

    public function getName()
    {
        return 'ft_corebundle_testresulttype';
    }
}
