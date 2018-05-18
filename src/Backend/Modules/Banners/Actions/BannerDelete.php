<?php

namespace Backend\Modules\Banners\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model;
use Backend\Form\Type\DeleteType;
use Backend\Modules\Banners\Domain\Banner\Command\DeleteBanner;
use Backend\Modules\Banners\Domain\Banner\Banner;

final class BannerDelete extends ActionDelete
{
    public function execute(): void
    {
        $deleteForm = $this->createForm(DeleteType::class, null, ['module' => $this->getModule()]);
        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(
                $this->getBackLink(
                    ['error' => 'non-existing']
                )
            );

            return;
        }
        $deleteFormData = $deleteForm->getData();

        /** @var Banner $banner */
        $banner = $this->getBanner((string) $deleteFormData['id']);

        // Handle the Banner delete
        $this->get('command_bus')->handle(new DeleteBanner($banner));

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'deleted',
                ]
            )
        );
    }

    /**
     * @param string $bannerId
     * @throw \Exception
     * @return Banner
     */
    private function getBanner(string $bannerId): Banner
    {
        try {
            /** @var Banner */
            return $this->get('banners.repository.banner')->find($bannerId);
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
