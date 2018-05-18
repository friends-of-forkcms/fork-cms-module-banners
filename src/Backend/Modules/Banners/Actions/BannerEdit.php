<?php

namespace Backend\Modules\Banners\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Locale;
use Backend\Form\Type\DeleteType;
use Backend\Modules\Banners\Domain\Banner\Command\UpdateBanner;
use Backend\Modules\Banners\Domain\Banner\Banner;
use Backend\Modules\Banners\Domain\Banner\BannerType;

final class BannerEdit extends ActionEdit
{
    public function execute(): void
    {
        parent::execute();

        /** @var Banner $bannerCase */
        $bannerCase = $this->getBanner();

        $deleteForm = $this->createForm(
            DeleteType::class,
            ['id' => $bannerCase->getId()],
            ['module' => $this->getModule(), 'action' => 'BannerDelete']
        );
        $this->template->assign('deleteForm', $deleteForm->createView());

        $form = $this->createForm(
            BannerType::class,
            new UpdateBanner(
                $bannerCase
            )
        );

        $form->handleRequest($this->getRequest());

        if (!$form->isValid()) {
            $this->template->assign('form', $form->createView());
            $this->template->assign('locale', Locale::workingLocale());
            $this->template->assign('bannerCase', $bannerCase);
            $this->template->assign('backLink', $this->getBackLink());

            $this->parse();
            $this->display('/Banners/Resources/views/Backend/Banner/Edit.html.twig');

            return;
        }

        /** @var UpdateBanner $updateBanner */
        $updateBanner = $form->getData();

        // Handle the Banner update
        $this->get('command_bus')->handle($updateBanner);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'edited',
                    'var' => $updateBanner->getBannerEntity()->getTranslation(Locale::workingLocale())->getTitle(),
                    'highlight' => 'row-' . $updateBanner->getBannerEntity()->getId(),
                ]
            )
        );
    }

    /**
     * @throw \Exception
     * @return Banner
     */
    private function getBanner(): Banner
    {
        try {
            /** @var Banner */
            return $this->get('banners.repository.banner')->find($this->getRequest()->query->get('id'));
        } catch (\Exception $e) {
            return $this->redirect(
                $this->getBackLink(
                    [
                        'error' => 'non-existing',
                    ]
                )
            );
        }
    }

    private function getBackLink(array $parameters = []): string
    {
        return Model::createUrlForAction(
            'BannerIndex',
            null,
            null,
            $parameters
        );
    }
}
