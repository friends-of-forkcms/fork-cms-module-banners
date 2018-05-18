<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\Banner;
use Backend\Modules\Banners\Domain\Banner\BannerDataTransferObject;

final class UpdateBanner extends BannerDataTransferObject
{
    public function __construct(Banner $banner)
    {
        parent::__construct($banner);
    }
}
