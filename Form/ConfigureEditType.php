<?php

namespace RetailCrm\DeliveryModuleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigureEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('connectionId', null, [
                'label'     => 'label.connectionId',
                'required'  => true,
                'attr' => [
                    'placeholder' => 'label.connectionId'
                ]
            ])
            ->add('crmKey', null, [
                'label'     => 'label.crmKey',
                'required'  => true,
                'attr' => [
                    'placeholder' => 'label.crmKey'
                ]
            ]);
    }
}
