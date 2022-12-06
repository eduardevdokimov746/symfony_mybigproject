<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\FormType;

use App\Container\AdminSection\User\Listener\ChangeAvatarListener;
use App\Container\Profile\Data\DTO\UpdateProfileAuthUserDTO;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Data\DTO\Trait\UserDTOTrait;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Form\DataTransformer\DefaultValueDataTransformer;
use App\Ship\Form\FormType\CheckboxFormType;
use App\Ship\Form\FormType\ImageFormType;
use App\Ship\Helper\Constraint;
use App\Ship\Parent\FormType;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use App\Ship\Validator\Constraints\UniqueConstraint;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends FormType
{
    public function __construct(
        #[Autowire('%csrf_token_id%')]
        protected string $csrfTokenId,
        #[Autowire('%kernel.environment%')]
        protected string $env,
        protected CreateUserTask $createUserTask,
        protected CreateProfileTask $createProfileTask,
        protected ChangeAvatarListener $changeAvatarListener,
        protected ChangeUserPasswordTask $changeUserPasswordTask
    ) {
        parent::__construct($this->csrfTokenId, $this->env);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', EnumType::class, [
                'label_format' => 'form.role',
                'empty_data' => '',
                'class' => RoleEnum::class,
                'choice_label' => static fn (RoleEnum $choice): string => $choice->translationKey(),
            ])
            ->add('login', TextType::class, [
                'label_format' => 'form.login',
                'empty_data' => '',
                'constraints' => $this->setExceptToUniqueConstraint($builder, Constraint::fromProperty(UserDTOTrait::class, 'login')),
            ])
            ->add('email', EmailType::class, [
                'label_format' => 'form.email',
                'empty_data' => '',
                'constraints' => $this->setExceptToUniqueConstraint($builder, Constraint::fromProperty(UserDTOTrait::class, 'email')),
            ])
            ->add('plain_password', TextType::class, [
                'label_format' => 'form.plain_password',
                'empty_data' => $builder->getData()?->getPassword(), /** @phpstan-ignore-line */
                'constraints' => Constraint::fromProperty(UserDTOTrait::class, 'plainPassword'),
                'getter' => static fn (): string => '',
                'setter' => function (User $user, ?string $plainPassword): void {
                    if (null !== $plainPassword && $user->getPassword() !== $plainPassword) {
                        $this->changeUserPasswordTask->lazy()->run($user, $plainPassword);
                    }
                },
            ])
            ->add('active', CheckboxFormType::class, [
                'label_format' => 'form.active',
                'setter' => static function (User $user, bool $active): void {
                    $active ? $user->activate() : $user->disable();
                },
            ])
            ->add('email_verified', CheckboxFormType::class, [
                'label_format' => 'form.email_verified',
            ])
            ->add('first_name', TextType::class, [
                'property_path' => 'profile.firstName',
                'label_format' => 'form.first_name',
                'empty_data' => null,
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'firstName'),
            ])
            ->add('last_name', TextType::class, [
                'property_path' => 'profile.lastName',
                'label_format' => 'form.last_name',
                'empty_data' => null,
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'lastName'),
            ])
            ->add('patronymic', TextType::class, [
                'property_path' => 'profile.patronymic',
                'label_format' => 'form.patronymic',
                'empty_data' => null,
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'patronymic'),
            ])
            ->add('about', TextType::class, [
                'property_path' => 'profile.about',
                'label_format' => 'form.about',
                'empty_data' => null,
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'about'),
            ])
            ->add('avatar', ImageFormType::class, [
                'mapped' => false,
                'label_format' => 'form.avatar',
                'empty_data' => null,
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'avatar'),
                'package' => ImageStorageEnum::Avatar->value,
                'with_preview' => $options['with_preview'],
            ])
        ;

        /** @phpstan-ignore-next-line  */
        if ($options['with_delete_avatar']) {
            $builder->add('delete_avatar', CheckboxFormType::class, [
                'mapped' => false,
                'label_format' => 'form.delete_avatar',
                'constraints' => Constraint::fromProperty(UpdateProfileAuthUserDTO::class, 'deleteAvatar'),
            ]);
        }

        $builder->get('active')->addModelTransformer(
            new DefaultValueDataTransformer($builder, User::DEFAULT_ACTIVE)
        );

        $builder->get('email_verified')->addModelTransformer(
            new DefaultValueDataTransformer($builder, User::DEFAULT_EMAIL_VERIFIED)
        );

        $builder->get('role')->addModelTransformer(
            new DefaultValueDataTransformer($builder, User::DEFAULT_ROLE)
        );

        $builder->addEventSubscriber($this->changeAvatarListener);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class,
            'empty_data' => $this->getEmptyDataCallback(),
            'required' => false,
            'translation_domain' => 'user',
            'with_delete_avatar' => false,
            'with_preview' => ImageFormType::WITH_PREVIEW,
        ]);

        $resolver->setAllowedTypes('with_delete_avatar', 'bool');
    }

    private function getEmptyDataCallback(): callable
    {
        return function (FormInterface $form): User {
            /** @var string $login */
            $login = $form->get('login')->getData() ?? '';

            /** @var string $email */
            $email = $form->get('email')->getData() ?? '';

            /** @var string $plainPassword */
            $plainPassword = $form->get('plain_password')->getData() ?? '';

            $user = $this->createUserTask->lazy()->run(
                $login,
                $email,
                $plainPassword,
                static function (User $user) use ($form): void {
                    /** @var bool $active */
                    $active = $form->get('active')->getData();

                    /** @var bool $emailVerified */
                    $emailVerified = $form->get('email_verified')->getData();

                    /** @var RoleEnum $role */
                    $role = $form->get('role')->getData();

                    $active ? $user->activate() : $user->disable();
                    $user->setEmailVerified($emailVerified);
                    $user->setRole($role);
                }
            );

            $this->createProfileTask->lazy()->run(
                $user,
                static function (Profile $profile) use ($form): void {
                    /** @var ?string $firstName */
                    $firstName = $form->get('first_name')->getData();

                    /** @var ?string $lastName */
                    $lastName = $form->get('last_name')->getData();

                    /** @var ?string $patronymic */
                    $patronymic = $form->get('patronymic')->getData();

                    /** @var ?string $about */
                    $about = $form->get('about')->getData();

                    $profile
                        ->setFirstName($firstName)
                        ->setLastName($lastName)
                        ->setPatronymic($patronymic)
                        ->setAbout($about)
                    ;
                }
            );

            return $user;
        };
    }

    /**
     * @param list<object> $constraints
     *
     * @return list<object>
     */
    private function setExceptToUniqueConstraint(FormBuilderInterface $builder, array $constraints): array
    {
        /** @var ?User $user */
        $user = $builder->getData();

        if (null === $user) {
            return $constraints;
        }

        $filterCallback = static fn (object $constraint): bool => $constraint instanceof UniqueConstraint;

        /** @var UniqueConstraint $uniqueConstraint */
        $uniqueConstraint = array_values(array_filter($constraints, $filterCallback))[0];
        $uniqueConstraint->exceptCriteria = ['id' => $user->getId()];

        return $constraints;
    }
}
