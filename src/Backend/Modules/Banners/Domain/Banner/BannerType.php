<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Backend\Core\Engine\TemplateModifiers;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslationType;
use Common\Form\CollectionType;
use Common\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'status',
                ChoiceType::class,
                [
                    'label' => 'lbl.Status',
                    'choices' => Status::getPossibleValues(),
                    'choices_as_values' => true,
                    'choice_label' => function($status) {
                        return TemplateModifiers::toLabel($status);
                    },
                    'choice_translation_domain' => false,
                ]
            )
            ->add(
                'image',
                ImageType::class,
                [
                    'label' => 'lbl.Image',
                    'image_class' => Image::class,
                    'required' => true,
                ]
            )
            ->add(
                'translations',
                CollectionType::class,
                [
                    'entry_type' => BannerTranslationType::class,
                    'error_bubbling' => false,
                    'constraints' => [new Valid()],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => BannerDataTransferObject::class,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'banners_banner';
    }
}
