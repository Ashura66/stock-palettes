<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\StockMovement;
use App\Enum\MovementType;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockMovementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'label' => 'Produit',
                'choice_label' => 'name',
                'query_builder' => fn (ProductRepository $repo) => $repo->createQueryBuilder('p')
                    ->andWhere('p.isActive = true')
                    ->orderBy('p.name', 'ASC'),
            ])
            ->add('type', EnumType::class, [
                'class' => MovementType::class,
                'label' => 'Type',
                'choice_label' => fn (MovementType $type) => $type->label(),
            ])
            ->add('quantity', IntegerType::class, ['label' => 'Quantité'])
            ->add('reason', TextType::class, [
                'label' => 'Motif (obligatoire pour un ajustement ou un déchet)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => StockMovement::class]);
    }
}