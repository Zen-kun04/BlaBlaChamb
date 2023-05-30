<?php

namespace App\Form;

use App\Entity\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actual_password', PasswordType::class)
            ->add('new_password', PasswordType::class, [
                'label' => 'New Password',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6]),
                    new Callback([$this, 'validatePasswords']),
                ],
            ])
            ->add('repeat_new_password', PasswordType::class, [
                'label' => 'Repeat new Password',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change password',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
        ]);
    }

    public function validatePasswords($pwd, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $newPassword = $form->get('new_password')->getData();
        $repeatPassword = $form->get('repeat_new_password')->getData();

        if ($newPassword !== $repeatPassword) {
            $context->buildViolation('Passwords must match')
                ->atPath('new_password')
                ->addViolation();
        }
    }
}
