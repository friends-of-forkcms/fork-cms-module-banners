<?php

namespace Backend\Modules\Banners\Installer;

use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\Banners\Domain\Banner\Banner;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslation;
use Common\ModuleExtraType;

/**
 * Installer for the banners module
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('Banners');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureEntities();
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->configureFrontendExtras();
    }

    protected function configureBackendNavigation(): void
    {
        // Set navigation for "Modules"
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $this->setNavigation(
            $navigationModulesId,
            $this->getModule(),
            'banners/banner_index',
            [
                'banners/banner_add',
                'banners/banner_edit'
            ]
        );
    }

    protected function configureBackendRights(): void
    {
        // Module rights
        $this->setModuleRights(1, $this->getModule());

        // Action rights for "Banner"
        $this->setActionRights(1, $this->getModule(), 'BannerAdd');
        $this->setActionRights(1, $this->getModule(), 'BannerDelete');
        $this->setActionRights(1, $this->getModule(), 'BannerEdit');
        $this->setActionRights(1, $this->getModule(), 'BannerIndex');
    }

    protected function configureFrontendExtras(): void
    {
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'Banners', 'Carousel', ['edit_url' => Model::getUrlForBlock('Banners', 'BannerIndex')]);
    }

    protected function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClasses(
            [
                Banner::class,
                BannerTranslation::class,
            ]
        );
    }
}
