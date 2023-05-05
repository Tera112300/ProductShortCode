<?php

namespace Plugin\ProductShortCode\Form\Type\Admin;

use Plugin\ProductShortCode\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Eccube\Entity\Product;
use Doctrine\ORM\EntityRepository;

use Plugin\ProductShortCode\Entity\Config;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Product',EntityType::class, [
            'class' => Product::class, 
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')->where('p.Status = :status')->setParameter('status',1);
            },
            'constraints' => [
                new NotBlank(),
            ],
        ])->add('sort_no',HiddenType::class, [
            'constraints' => [
                new NotBlank(),
                new PositiveOrZero(),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}
