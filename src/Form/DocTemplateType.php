<?php
namespace App\Form;

use App\Entity\DocTemplate;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\File;

use FOS\CKEditorBundle\Form\Type\CKEditorType;

class DocTemplateType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$entity=$builder->getData();
		$builder
				->add('name',TextType::class,[
					'label'=>'template.name',
				])
				->add('document',CKEditorType::class,[
					'label'=>'template.document',
					'config'=>[
						'removeButtons' => 'Source,Save,NewPage,Preview,Print,Templates,document,Form,Checkbox,Radio,TextField, Textarea,Select,Button,ImageButton,HiddenField,Textarea,CreateDiv,Blockquote,Link,Unlink,Anchor,CreatePlaceholder,Image,Flash,Smiley,SpecialChar,Iframe,InsertPie,Format,Styles,About,Scayt,Language',
					],
					'required'=>true,
				])
				->add('subDoc',CKEditorType::class,[
					'label'=>'template.subDoc',
					'config'=>[
						'removeButtons' => 'Source,Save,NewPage,Preview,Print,Templates,document,Form,Checkbox,Radio,TextField, Textarea,Select,Button,ImageButton,HiddenField,Textarea,CreateDiv,Blockquote,Link,Unlink,Anchor,CreatePlaceholder,Image,Flash,Smiley,SpecialChar,Iframe,InsertPie,Format,Styles,About,Scayt,Language',
					],
					'required'=>true,
				])
				->add('active',CheckboxType::class,[
					'required'=>false,
					'label'=>'template.active',
				])
				;
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'=>DocTemplate::class
		]);
	}
}
