<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\FundCategories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('user_name')
            ->add('user_surname')
            ->add('phone')
            ->add('telegram')
            ->add('company_name')
            ->add('categories', EntityType::class, [
                'class' => FundCategories::class,
                'label' => 'Направление фонда',
                 'multiple' => true,
                 'choice_label' => 'name',
                 'choice_value' => 'id'

                ])
            ->add('logotype', FileType::class, [
                'label' => 'Логотип',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/x-citrix-png',
                            'image/x-png',
                            'image/jpeg',
                            'image/pjpeg',
                            'image/x-citrix-jpeg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Можно загрузить только форматы jpp, jpeg, png, webp',
                        'maxSizeMessage' => 'Максимальный размер файла: 1024 килобайт',
                    ])
                ]])
            ->add('slogan')
            ->add('description')
            ->add('site')
            ->add('city')
            ->add('login_date', null, [
                'widget' => 'single_text',
            ])
            ->add('hide_contacts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
