<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Chanson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class ChansonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateSortie', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'my-date-class'],
                'input' => 'datetime_immutable',
            ])
            ->add('genre', TextType::class)
            ->add('langue', TextType::class)
            ->add('photoCouverture', UrlType::class)
            ->add('artistes', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nom', // ou tout autre propriété de l'entité Artiste
                'multiple' => true,
                'expanded' => true, // Mettre à true pour une liste de cases à cocher
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chanson::class,
        ]);
    }
}