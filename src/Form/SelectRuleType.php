<?php

namespace App\Form;

use App\Entity\Rule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectRuleType extends AbstractType
{

    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security) {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ruleRepository = $this->entityManager->getRepository(Rule::class);
        $userRules = $ruleRepository->findBy(['author' => $this->security->getUser()]);
        $rules = [];
        foreach ($userRules as $rule) {
            $rules[$rule->getName()] = $rule->getId();
        }
        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $rules
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rule::class,
        ]);
    }
}
