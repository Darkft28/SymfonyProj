<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('content', TextareaType::class, ['label' => 'Contenu', 'attr' => ['rows' => 10]])
            ->add('slug', TextType::class, ['label' => 'Slug'])
            ->add('image', TextType::class, ['label' => 'Image (URL)', 'required' => false])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => ['Brouillon' => 'draft', 'Publié' => 'published'],
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getFirstName() . ' ' . $u->getLastName(),
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Aucune catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Article::class]);
    }
}
