<?php

namespace App\Form;

use App\Entity\Ride;
use App\Entity\Rule;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRideType extends AbstractType
{
    private User $user;
    public function __construct(Security $security) {
        $this->user = $security->getUser();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->user;
        $builder
            ->add('departure')
            ->add('destination')
            ->add('seats')
            ->add('price')
            ->add('date')
            ->add('rule', EntityType::class, [
                'class' => Rule::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $rep) use ($user) {
                    return $rep->createQueryBuilder('rule')
                    ->where('rule.author = :author')
                    ->setParameter('author', $user);
                }
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
