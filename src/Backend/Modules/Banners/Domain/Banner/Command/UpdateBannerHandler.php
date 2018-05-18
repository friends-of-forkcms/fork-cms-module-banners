<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\Banner;

final class UpdateBannerHandler
{
    public function handle(UpdateBanner $updateBanner): void
    {
        /** @var Banner $bannerCase */
        $bannerCase = Banner::fromDataTransferObject($updateBanner);

        // Set the entity, so we can use it in an action
        $updateBanner->setBannerEntity($bannerCase);
    }
}
