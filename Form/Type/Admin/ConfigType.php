<?php

namespace Plugin\ProductShortCode\Form\Type\Admin;

use Plugin\ProductShortCode\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Plugin\ProductShortCode\Form\Type\Admin\OptionType;


class ConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ])->add('content', TextareaType::class, [
            'attr' => ['rows' => 6],
            'required' => false,
        ])->add('Options', CollectionType::class, [
            'entry_type' => OptionType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            // 'by_reference' => false,
        ]);

       
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
