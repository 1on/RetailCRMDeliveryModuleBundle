<?php

namespace RetailCrm\DeliveryModuleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ParcelType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'connection',
                EntityType::class,
                [
                    'class' => $options['connection_class'],
                    'label' => 'label.connection',
                    'translation_domain' => 'messages'
                ]
            )
            ->add(
                'orderId',
                TextType::class,
                [
                    'label' => 'label.orderId',
                    'translation_domain' => 'messages'
                ]
            )
            ->add(
                'trackId',
                TextType::class,
                [
                    'label' => 'label.trackId',
                    'translation_domain' => 'messages'
                ]
            )
            ->add(
                'isClosed',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'label.isClosed',
                    'translation_domain' => 'messages'
                ]
            );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['connection_class']);
    }
}
