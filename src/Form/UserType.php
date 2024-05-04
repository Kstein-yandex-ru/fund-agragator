<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('type')
            ->add('user_name')
            ->add('user_surname')
            ->add('phone')
            ->add('telegram')
            ->add('company_name')
            ->add('logotype')
            ->add('slogan')
            ->add('description')
            ->add('site')
            ->add('city')
            ->add('registration_date', null, [
                'widget' => 'single_text',
            ])
            ->add('login_date', null, [
                'widget' => 'single_text',
            ])
            ->add('hide_contacts')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
