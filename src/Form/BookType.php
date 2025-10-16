<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref', TextType::class, [
                'label' => 'Reference'
            ])
            ->add('title', TextType::class, [
                'label' => 'Title'
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
                'placeholder' => 'Choose a category',
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Published',
                'required' => false,
                'data' => true, // Set to true by default
            ])
            ->add('publicationDate', DateType::class, [
                'label' => 'Publication Date',
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'label' => 'Author',
                'class' => Author::class,
                'choice_label' => 'username',
                'placeholder' => 'Choose an author',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}