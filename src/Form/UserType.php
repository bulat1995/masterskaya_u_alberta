<?php
namespace App\Form;

use App\Entity\User;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
	{
		global $entity;
		$entity = $builder->getData();
		$builder->add('roles',ChoiceType::class,[
					'label'=>'user.rules',
                    'multiple'=>true,
                    'attr'=>[
                        'size'=>22,
                    ],
                    'choices'  =>array_flip(User::rolesName)
				])
                ->add('enabled',CheckboxType::class,[
					'label'=>'user.active'
				]);
	}



	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>User::class
		]);
	}


}
