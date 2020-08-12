<?php
namespace App\Form;

use App\Entity\Document;
use App\Entity\DocTemplate;
use App\Entity\Catalog;
use App\Entity\Client;
use App\Entity\Work;


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

use Doctrine\ORM\EntityRepository;

class DocumentType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		global $entity;
		$entity = $builder->getData();

		$builder->add('number',TextType::class,[
					'label'=>'number',
					'required'=>false,
					'constraints'=>[
						new NotBlank(),
					]
				])
				->add('dateOpen',DateType::class,[
					'label'=>'document.dateOpen',
					'widget' => 'single_text',
				])
				->add('works',EntityType::class,[
				    'class' => Work::class,
				    'required'=>true,
				    'attr'=>['size'=>20],
				    'by_reference'=>false,
				    'multiple'=>true,
					'label'=>'works',
					'choice_label' => function($work){
						return $work->getDateCreate()->format("d.m.Y").' '.$work->getCatalog()->getName().' ['.$work->getCount().' '. $work->getCatalog()->getCountName().'] Сумма: '.$work->getPrice()*$work->getCount().'₽';
					},
					'choice_attr' => function($choice, $key, $value) {
				        // adds a class like attending_yes, attending_no, etc
				        return ['attr-price' => strtolower($choice->getFullPrice())];
				    },
					'query_builder' => function (EntityRepository $er) {
						global $entity;
				       		return $er->createQueryBuilder('u')
								->leftJoin('u.catalog','cat')
								->leftJoin('cat.docTemplate','docTemp')
								->where('u.document = :doc OR u.document is NULL')
								->setParameter('doc',$entity->getId())
								->andWhere('cat.id = u.catalog')
								->andWhere('u.client = :client')
								->setParameter('client',$entity->getClient()->getId())
								->andWhere('docTemp.id = :tempId')
								->setParameter('tempId',$entity->getDocTemplate()->getId())
								->orderBy('u.dateCreate','ASC');
				    },
					'constraints'=>[
						new Count(['min'=>1]),
					]
				]);
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>Document::class
		]);
	}
}
