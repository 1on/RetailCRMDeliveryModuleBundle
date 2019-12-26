<?php

namespace RetailCrm\DeliveryModuleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnectionType extends AbstractType
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
            ->add('crmUrl', TextType::class, [
                'label'     => 'label.crmUrl',
                'required'  => true,
                'attr'      => [
                    'placeholder'   => 'label.crmUrl',
                    'pattern'       => '^(https?:\/\/)?([\da-z0-9\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$',
                ],
                'translation_domain' => 'messages'
            ])
            ->add('crmKey', TextType::class, [
                'label'     => 'label.crmKey',
                'required'  => true,
                'attr'      => [
                    'placeholder' => 'label.crmKey'
                ],
                'translation_domain' => 'messages'
            ])
            ->add('isActive', CheckboxType::class, [
                'label'     => 'label.isActive',
                'required'  => false,
                'translation_domain' => 'messages'
            ])
            ->add('language', ChoiceType::class, [
                'label'     => 'label.language',
                'choices'   => [
                    'RU'    => 'ru',
                    'EN'    => 'en',
                    'ES'    => 'es'
                ],
                'required'  => true,
                'translation_domain' => 'messages'
            ])
            ->add('isFreeze', CheckboxType::class, [
                'label'     => 'label.isFreeze',
                'required'  => false,
                'translation_domain' => 'messages'
            ]);

        if ($options['is_admin']) {
            $builder
                ->add('debug', CheckboxType::class, [
                    'label'     => 'label.debug',
                    'required'  => false,
                    'translation_domain' => 'messages'
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['container', 'is_admin']);
    }
}
