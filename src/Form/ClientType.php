<?php
namespace App\Form;

use App\Entity\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
class ClientType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		global $entity;
		$entity = $builder->getData();
		$builder->add('companyName',TextareaType::class,[
					'label'=>'client.companyName',
				])
				->add('address',TextareaType::class,[
					'label'=>'client.address',
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
					'placeholder'=>'Главы Администрации Кастрюлина Василия Тазиковича'
					]
				])
				->add('docName',TextType::class,[
					'label'=>'client.docName',
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
				;

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Client::class
		]);
	}
}
