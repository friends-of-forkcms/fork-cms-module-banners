<?php

namespace Backend\Modules\Banners\Actions;

use Backend\Core\Engine\Base\ActionIndex;
use Backend\Modules\Banners\Domain\Banner\BannerDataGrid;

final class BannerIndex extends ActionIndex
{
    public function execute(): void
    {
        parent::execute();

        $this->template->assign('dataGrid', BannerDataGrid::getHtml());

        $this->parse();
        $this->display('/Banners/Resources/views/Backend/Banner/Index.html.twig');
    }
}
