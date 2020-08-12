<?php
namespace App\Form;

use App\Entity\Work;
use App\Entity\Catalog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class WorkType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('catalog',EntityType::class,[
					'class'=>Catalog::class,
					'label'=>'catalog.name',
					'choice_label'=>'name',
					'placeholder' => 'Выберите услугу',
					'choice_label' => function($catalog){
						return $catalog->getName();
					},
				    'choice_attr' => function($choice, $key, $value) {
				        return ['attr-price' => strtolower($choice->getPrice())];
				    },
					'constraints'=>[
						new NotBlank(),
					],

				])
				->add('count',IntegerType::class,[
					'label'=>'count',
					'empty_data' => 1,
				    'attr' => [
				        'min' => 1,
				    ],
					'constraints'=>[
						new NotBlank(),
					],
				])
				->add('fullPrice',MoneyType::class,[
					'label'=>'fullPrice',
					'disabled'=>true,
					'currency'=>false,
					'constraints'=>[
						new NotBlank(),
					],
				])
				->add('price',MoneyType::class,[
						'label'=>'price',
					  'currency' => '',
					  'attr'=>[
						  'inputmode'=>'numeric',
					  ],
					  'constraints'=>[
					  	new GreaterThanOrEqual(0),
					  ]
				])
				;
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Work::class
		]);
	}
}
