<?php

namespace Backend\Modules\Banners\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Locale;
use Backend\Modules\Banners\Domain\Banner\Command\CreateBanner;
use Backend\Modules\Banners\Domain\Banner\BannerType;

final class BannerAdd extends ActionAdd
{
    public function execute(): void
    {
        parent::execute();

        $form = $this->createForm(
            BannerType::class,
            new CreateBanner()
        );

        $form->handleRequest($this->getRequest());

        if (!$form->isValid()) {
            $this->template->assign('form', $form->createView());
            $this->template->assign('backLink', $this->getBackLink());

            $this->parse();
            $this->display('/Banners/Resources/views/Backend/Banner/Add.html.twig');

            return;
        }

        /** @var CreateBanner $createBanner */
        $createBanner = $form->getData();

        // Handle the Banner create
        $this->get('command_bus')->handle($createBanner);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'added',
                    'highlight' => 'row-' . $createBanner->getBannerEntity()->getId(),
                ]
            )
        );
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
