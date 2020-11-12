<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\Offre;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class OffreType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('mission', TextareaType::class )
            ->add('lieu')
            ->add('salaire')
            ->add('date', DateType::class, [
                    'widget' => 'choice',
                    'format' => 'yyyy-MM-dd',
            ])
            ->add('isValid', CheckboxType::class, [
                'label' => 'Cette offre est d\'actualité',
                'required' => false
            ])

            ->add('user', EntityType::class , [
                'class' => User::class,
                'choice_label' => 'email'
            ])
            
            ->add('saveme', SubmitType::class, [
                'attr' => ['class' => 'btn-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
    
}