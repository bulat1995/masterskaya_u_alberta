<?php
namespace App\Form;

use App\Entity\Act;
use App\Entity\Work;
use App\Entity\Client;
use App\Form\WorkType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ActType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('client',EntityType::class,[
					'required'=>true,
					'class'=>Client::class,
					'choice_label'=>function($client){
						return $client->getCompanyName();
					},
					'constraints'=>[
						new NotBlank(),
					]
				])
				->add('worksForAct', CollectionType::class, [
				    // each entry in the array will be an "email" field
				    'entry_type' => WorkType::class,
				    'required'=>true,
				    'label'=>false,
				    'entry_options'=>[
				    	'label'=>false
				    ],
				    'by_reference'=>false,
					'allow_add' => TRUE,
					'allow_delete' => TRUE,
				    'prototype_data'=>new Work(),
					'constraints'=>[
						new Count(['min'=>1]),
					]
				]);
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Act::class
		]);
	}
}
