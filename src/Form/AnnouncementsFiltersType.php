<?php

namespace App\Form;

use App\Entity\Ride;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnouncementsFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Ascendant' => 'ASC',
                    'Descendant' => 'DESC',
                ],
                'required' => false
            ])
            ->add('destination', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Ascendant' => 'ASC',
                    'Descendant' => 'DESC',
                ],
                'required' => false
            ])
            ->add('date', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Ascendant' => 'ASC',
                    'Descendant' => 'DESC',
                ],
                'required' => false
            ])
            ->add('creation', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Ascendant' => 'ASC',
                    'Descendant' => 'DESC',
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
