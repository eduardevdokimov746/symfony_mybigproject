<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\FormType;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Form\DataTransformer\DefaultValueDataTransformer;
use App\Ship\Form\FormType\CheckboxFormType;
use App\Ship\Parent\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryFormType extends FormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Length(max: 45),
            new Assert\Regex('#[\d]+#', match: false, message: 'only_alpha_with_spaces'),
        ];

        $builder
            ->add('ru_name', TextType::class, [
                'label_format' => 'form.title',
                'label_translation_parameters' => ['%lang%' => 'рус'],
                'empty_data' => '',
                'constraints' => $constraints,
            ])
            ->add('en_name', TextType::class, [
                'label_format' => 'form.title',
                'label_translation_parameters' => ['%lang%' => 'eng'],
                'empty_data' => '',
                'constraints' => $constraints,
            ])
            ->add('active', CheckboxFormType::class, [
                'label_format' => 'form.active',
            ])
        ;

        $builder->get('active')->addModelTransformer(
            new DefaultValueDataTransformer($builder, Category::DEFAULT_ACTIVE)
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Category::class,
            'empty_data' => $this->getEmptyDataCallback(),
            'required' => false,
            'translation_domain' => 'category',
        ]);
    }

    private function getEmptyDataCallback(): callable
    {
        return static function (FormInterface $form): Category {
            /** @var string $ruName */
            $ruName = $form->get('ru_name')->getData() ?? '';

            /** @var string $enName */
            $enName = $form->get('en_name')->getData() ?? '';

            /** @var bool $active */
            $active = $form->get('active')->getData();

            return (new Category($ruName, $enName))->setActive($active);
        };
    }
}
