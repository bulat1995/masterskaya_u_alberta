<?php
namespace App\Form;

use App\Entity\Catalog;
use App\Entity\Device;
use App\Entity\DocTemplate;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CatalogType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name',TextType::class,[
					'label'=>'name',
					'constraints'=>[
						new NotBlank(),
						new Length(['min'=>2,'max'=>400])
					]
				])
				->add('device',EntityType::class,[
							'class' => Device::class,
							'placeholder'=>'Корневая категория',
							'label'=>'device',
							'query_builder' => function (EntityRepository $er) {
						        return $er->createQueryBuilder('u')->orderBy('u.lft', 'ASC');
						    },
							'choice_label' => function($device){
								return str_repeat("&nbsp;", $device->getLvl()*5).$device->getName();
							},
				])
				->add('docTemplate',EntityType::class,[
							'class' => DocTemplate::class,
							'placeholder'=>'Выберите шаблон',
							'label'=>'catalog.forDocument',
							'multiple'=>true,
							'choice_label' => function($device){
								return $device->getName();
							},
							'query_builder' => function (EntityRepository $er) {
								global $entity;
									return $er->createQueryBuilder('t')
										->where('t.active =1');
							},
				])
				->add('price',MoneyType::class,[
						'label'=>'price',
					  'currency' => 'RUB',
					  'constraints'=>[
					  	new GreaterThanOrEqual(0),
					  ]
				])
				->add('countName',TextType::class,[
					'label'=>'countName',
					'constraints'=>[
						new NotBlank(),
						new Length(['max'=>100])
					]
				]);

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Catalog::class
		]);
	}
}
