<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\Banner;
use Backend\Modules\Banners\Domain\Banner\BannerRepository;

final class CreateBannerHandler
{
    /** @var BannerRepository */
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function handle(CreateBanner $createBanner): void
    {
        /** @var Banner $bannerCase */
        $bannerCase = Banner::fromDataTransferObject($createBanner, $this->bannerRepository->getNextSequence());
        $this->bannerRepository->add($bannerCase);

        // Set the entity, so we can use it in an action
        $createBanner->setBannerEntity($bannerCase);
    }
}
