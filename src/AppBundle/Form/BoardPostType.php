<?php
namespace AppBundle\Form;

use AppBundle\Entity\Board;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('userId', EntityType::class, ['class' => User::class, 'property_path' => 'user'])
            ->add('entity', IntegerType::class)
            ->add('title', TextType::class)
            ->add('contents', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Board::class,
            'csrf_protection' => false
        ));
    }
}