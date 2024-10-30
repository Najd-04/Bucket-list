<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $optionsmessenger_messages): void
  {
    $builder
      ->add('title')
      ->add('decription')
      ->add('author')
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => 'name',
        'placeholder' => 'Sélectionnez une catégorie',
        'multiple'=>true,
        'expanded'=>true,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Wish::class,
    ]);
  }
}
