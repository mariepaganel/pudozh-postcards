<?php
/**
 * Форма отправки открытки
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostcardSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('toName', TextType::class, [
                'label' => 'Кому (имя получателя)'
            ])
            ->add('toEmail', EmailType::class, [
                'label' => 'E-mail получателя',
                'help' => 'Куда отправить открытку'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Текст открытки'
            ])
            ->add('fromName', TextType::class, [
                'label' => 'Ваше имя'
            ])
            ->add('fromEmail', TextType::class, [
                'label' => 'Ваш e-mail',
                'help' => 'Получатель сможет ответить Вам'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Отправить',
                'attr' => ['class' => 'btn-primary btn-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostcardSend::class,
        ]);
    }
}