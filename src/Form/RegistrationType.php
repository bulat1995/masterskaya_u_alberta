<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
class RegistrationType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('userName',TextType::class)
				->add('email',TextType::class)
				->add('plainPassword', RepeatedType::class, array(
	                'type' => PasswordType::class,
	                'options' => array(
	                    'translation_domain' => 'FOSUserBundle',
	                    'attr' => array(
	                        'autocomplete' => 'new-password',
	                    ),
	                ),
	                'first_options' => array('label' => 'form.password'),
	                'second_options' => array('label' => 'form.password_confirmation'),
	                'invalid_message' => 'fos_user.password.mismatch',
	            ))	
				;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>User::class 
		]);
	}
}
