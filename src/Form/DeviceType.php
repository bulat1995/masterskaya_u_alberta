<?php

namespace App\Form;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Form\DeviceType;
use App\Service\FileUploader;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Image;


class DeviceType extends AbstractType
{


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		global $entity;
		$entity = $builder->getData();

		$category=['mapped'=>false,
					'class' => Device::class,
					'placeholder'=>'Корневая категория',
					'required' => false,
					'label'=>'device.category',
					'query_builder' => function (EntityRepository $er) {
				        return $er->createQueryBuilder('u')->orderBy('u.lft', 'ASC');
				    },													
					'choice_label' => function($device){
						return str_repeat("&nbsp;", $device->getLvl()*5).$device->getName();
					},
		];
		if($entity->getName()!=null)
		{
			   $category['choice_attr'] =  function($key, $val, $index) {
					    $status=[];
				    	global $entity;
					        $disabled = 0;
					        if( $entity->getLft()<=$key->getLft() &&  $entity->getRgt()>=$key->getRgt())
					        {
					        	$disabled=1;
					        }
					        if($entity->getLft()>$key->getLft() &&  $entity->getRgt()<$key->getRgt() )
					        {
					        	$disabled=2;
					        }
					        if($disabled==1)
						        $status['disabled'] ='disabled';
					        elseif($disabled==2)
					        	$status['selected']='selected';
					        return $status;
				};
		}
		$builder->add('name',TextType::class,[
						'label'=>'device.name',
						'constraints'=>[
		                    new NotBlank(),
							new Length(['min'=>2, 'max'=>400]),
						],
					])
				->add('category',EntityType::class,$category)
				->add('logo',FileType::class,[
					'label'=>'device.logo',
					'required' => false,
					'constraints'=>[
						new Image(),
					],
				])
				->add('deleteLogo',CheckboxType::class,
					[
						'label'=>'Удалить текущий логотип',
						'mapped'=>false,
						'required'=>false,
					])
				;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Device::class,
		]);
	}


}