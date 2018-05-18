<?php

namespace Backend\Modules\Banners\Domain\BannerTranslation;

use Backend\Modules\Banners\Domain\Banner\Banner;
use Common\Core\Model;
use Common\Locale;
use Doctrine\ORM\EntityRepository;

class BannerTranslationRepository extends EntityRepository
{
    public function add(BannerTranslation $bannerTranslation): void
    {
        // We don't flush here, see http://disq.us/p/okjc6b
        $this->getEntityManager()->persist($bannerTranslation);
    }
}
