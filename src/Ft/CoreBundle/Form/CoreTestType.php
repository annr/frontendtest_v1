<?php

namespace Ft\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CoreTestType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('class_name')
            ->add('package_name')
            ->add('heading')
            ->add('description')
            ->add('weight')
          //  ->add('extended_description')
            ->add('more_details')
            ->add('resources')
            ->add('notes')
            ->add('run_by_default')
           // ->add('enabled')
           // ->add('print_line_numbers')
           // ->add('print_details')
            ->add('created')
           // ->add('updated')
        ;
    }

    public function getName()
    {
        return 'ft_corebundle_coretesttype';
    }
}
