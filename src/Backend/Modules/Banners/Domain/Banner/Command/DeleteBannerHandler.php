<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\BannerRepository;

final class DeleteBannerHandler
{
    /** @var BannerRepository */
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function handle(DeleteBanner $deleteBanner): void
    {
        $this->bannerRepository->remove($deleteBanner->getBanner());
    }
}
