<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\BannerDataTransferObject;

final class CreateBanner extends BannerDataTransferObject
{
    public function __construct()
    {
        parent::__construct();
    }
}
