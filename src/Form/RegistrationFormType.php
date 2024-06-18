<?php

namespace App\Form;

use App\Entity\FundCategories;
use App\Entity\User;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Благотворительный фонд' => 'fund',
                    'Коммерческая организация' => 'commerce',
                    'Частное лицо' => 'individual',
                ],
                'label' => 'Зарегистрироваться как',
                ])
                ->add('categories', EntityType::class, [
                    'class' => FundCategories::class,
                    'label' => 'Направление фонда',
                     'multiple' => true,
                     'choice_label' => 'name',
                     'choice_value' => 'id'

                    ])
            ->add('registration_date', DateType::class, [
                'data' => \DateTime::createFromFormat('Y-m-d', date('Y-m-d')),
                'attr' => array('style'=>'display:none'),
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
