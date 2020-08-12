<?php

namespace App\Form;

use App\Entity\Setting;

use App\Service\FileUploader;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

class SettingType extends AbstractType
{


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('qrCode',TextType::class,[
			'label'=>'qrCodeUrl',
			'constraints'=>[
				new Url(),
			]
		])
		->add('qrCodeWidth',IntegerType::class,[
			'label'=>'qrCodeWidth',
			'empty_data' => 1,
			'attr' => [
				'min' => 1,
			],
			'constraints'=>[
				new NotBlank(),
			],
		])
		->add('companyName',TextType::class,[
			'label'=>'client.companyName',
		])
		->add('positionNameShort',TextType::class,[
			'label'=>'client.positionNameShort',
		])
		->add('positionName',TextType::class,[
			'label'=>'client.positionName',
		])
		->add('bossName',TextType::class,[
			'label'=>'client.bossName',
			'attr'=>[
				'pattern'=>'([A-zА-я]+)\s([A-zА-я]+)\s([A-zА-я]+)',
			]
		])
		->add('inFace',TextType::class,[
			'label'=>'client.inFace',
			'attr'=>[
			'placeholder'=>'Индивидуального предпринимателя Петрова Петра Оксановича'
			]
		])
		->add('docName',TextType::class,[
			'label'=>'client.docName',
		])
		->add('address',TextareaType::class,[
			'label'=>'client.address',
		])
		->add('bankInfo',TextareaType::class,[
			'label'=>'client.bankInfo',
		])
		->add('inn',TextType::class,[
			'label'=>'client.inn',
		])
		->add('ks',TextType::class,[
			'label'=>'client.ks',
		])
		->add('bik',TextType::class,[
			'label'=>'client.bik',
		])
		->add('kpp',TextType::class,[
			'label'=>'client.kpp',
		])
		->add('contacts',TextareaType::class,[
			'label'=>'client.contacts',
		])
		->add('rss',TextType::class,[
			'label'=>'client.rss',
		])
		->add('siteEmail',TextType::class,[
			'label'=>'siteEmail',
		])
		->add('sitePhone',TextType::class,[
			'label'=>'sitePhone',
		])
		->add('schedule',TextareaType::class,[
			'label'=>'schedule',
		])
		->add('siteKeywords',TextareaType::class,[
			'label'=>'siteKeywords',
		])
		->add('siteAddress',TextareaType::class,[
			'label'=>'siteAddress',
		])
		->add('clientCount',IntegerType::class,[
			'label'=>'clientCount',
		])
		->add('startWorkYear',IntegerType::class,[
			'label'=>'startWorkYear',
		])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Setting::class,
		]);
	}


}
