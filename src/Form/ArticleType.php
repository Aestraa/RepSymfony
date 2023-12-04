<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content') 
            ->add('imageUrl', UrlType::class) // Permet de compléter automatiquement le champ avec un lien
            ->add('createdAt', DateTimeType::class, [
                'format' => 'dd/MM/yyyy HH:mm:ss', // Format de la date
                'input'  => 'datetime_immutable', // Type de champ
                'html5' => false, 
            ])
            ->add('author') 
            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}