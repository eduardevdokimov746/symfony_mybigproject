<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\FormType;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\FormType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryFormType extends FormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ru_name', TextType::class, [
                'label_format' => 'form.title',
                'label_translation_parameters' => ['%lang%' => 'рус'],
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 45),
                    new Assert\Regex('#[\d]+#', match: false, message: 'only_alpha_with_spaces'),
                ],
            ])
            ->add('en_name', TextType::class, [
                'label_format' => 'form.title',
                'label_translation_parameters' => ['%lang%' => 'eng'],
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 45),
                    new Assert\Regex('#[\d]+#', match: false, message: 'only_alpha_with_spaces'),
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label_format' => 'form.active',
                'label_attr' => ['class' => 'checkbox-inline'],
            ])
        ;

        $builder->get('active')->addModelTransformer(new CallbackTransformer(
            /** @phpstan-ignore-next-line */
            fn (bool $value) => $builder->getData() ? $value : Category::ACTIVE_DEFAULT,
            fn (bool $value) => $value
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Category::class,
            'empty_data' => function (FormInterface $form) {
                $ruName = $form->get('ru_name')->getData() ?? '';
                $enName = $form->get('en_name')->getData() ?? '';
                $active = $form->get('active')->getData();

                /** @phpstan-ignore-next-line */
                return (new Category($ruName, $enName))->setActive($active);
            },
            'required' => false,
            'translation_domain' => 'category',
        ]);
    }
}
