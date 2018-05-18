<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

use Backend\Modules\Banners\Domain\Banner\BannerRepository;

final class ReSequenceBannersHandler
{
    /** @var BannerRepository */
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function handle(ReSequenceBanners $reSequenceBanners): void
    {
        foreach ($reSequenceBanners->getIds() as $sequence => $id) {
            $banner = $this->bannerRepository->find($id);

            if ($banner === null) {
                continue;
            }

            $banner->setSequence($sequence);
        }
    }
}
