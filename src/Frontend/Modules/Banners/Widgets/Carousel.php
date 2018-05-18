<?php

namespace Frontend\Modules\Banners\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Language\Locale;

/**
 * This is a carousel to show the banners
 */
class Carousel extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();

        $this->template->assign('locale', Locale::frontendLanguage());
        $this->template->assign('banners', $this->get('banners.repository.banner')->findAllActive());

        $this->loadTemplate();
    }
}
