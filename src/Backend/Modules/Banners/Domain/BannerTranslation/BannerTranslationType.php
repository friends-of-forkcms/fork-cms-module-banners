<?php

namespace Backend\Modules\Banners\Domain\BannerTranslation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BannerTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'lbl.Title',
                ]
            )
            ->add(
                'subTitle',
                TextType::class,
                [
                    'label' => 'lbl.SubTitle',
                    'required' => false,
                ]
            )
            ->add(
                'hasLinkToUrl',
                CheckboxType::class,
                [
                    'label' => 'msg.ShouldHaveLinkToUrl',
                    'required' => false,
                ]
            )
            ->add(
                'linkToUrl',
                TextType::class,
                [
                    'label' => 'lbl.Url',
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BannerTranslationDataTransferObject::class,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                if ($data->hasLinkToUrl !== true) {
                    return ['Default'];
                }

                return ['Default', 'link_to_url_is_required'];
            },
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'banners_banner_translation';
    }
}
