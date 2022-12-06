<?php

declare(strict_types=1);

namespace App\Ship\Form\FormType;

use App\Container\User\Entity\Doc\User;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use LogicException;
use RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageFormType extends FileType
{
    public const WITH_PREVIEW = false;

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('with_preview', self::WITH_PREVIEW);
        $resolver->setDefault('package', null);

        $resolver->setAllowedValues('package', ImageStorageEnum::getAllValues());
        $resolver->setAllowedTypes('package', ['string', 'null']);
        $resolver->setAllowedTypes('with_preview', 'bool');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        $attr = [
            'with_preview' => $options['with_preview'],
            'package' => $options['package'],
        ];

        $data = $form->getParent()?->getData();

        if (null !== $data) {
            /** @phpstan-ignore-next-line */
            if ($options['with_preview'] && null === $options['package']) {
                throw new LogicException('In order to show the image, you must explicitly specify "package" option');
            }

            if (!is_object($data)) {
                throw new RuntimeException('Expected $data with type object, '.get_debug_type($data).' given');
            }

            $attr['image_name'] = $this->getImageName($data);
        }

        $view->vars['attr'] = $attr;
    }

    private function getImageName(object $data): string
    {
        return match (true) {
            $data instanceof User => $data->getProfile()->getAvatar(),
            default => throw new RuntimeException('Undefined '.get_class($data).' class')
        };
    }
}
