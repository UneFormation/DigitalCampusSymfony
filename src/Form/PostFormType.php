<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Titre'
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Contenu'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => function(Category $category) {
                    return $category->getTitle();
                },
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Catégorie'
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Auteur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
