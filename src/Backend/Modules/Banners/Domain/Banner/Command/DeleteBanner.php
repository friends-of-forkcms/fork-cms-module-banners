<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\Banner;

final class DeleteBanner
{
    /** @var Banner */
    private $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }
}
