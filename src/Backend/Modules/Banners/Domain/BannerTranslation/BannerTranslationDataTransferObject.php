<?php

namespace Backend\Modules\Banners\Domain\BannerTranslation;

use Backend\Modules\Banners\Domain\Banner\Banner;
use Common\Doctrine\Entity\Meta;
use Common\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class BannerTranslationDataTransferObject
{
    /** @var BannerTranslation */
    private $bannerTranslationEntity;

    /**
     * @var Locale
     */
    private $locale;

    /**
     * @var Banner
     */
    private $banner;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string|null
     */
    public $subTitle;

    /**
     * @var bool
     */
    public $hasLinkToUrl;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"link_to_url_is_required"})
     */
    public $linkToUrl;

    /**
     * @var Meta
     */
    public $meta;

    public function __construct(BannerTranslation $bannerTranslation = null, Locale $locale = null)
    {
        $this->bannerTranslationEntity = $bannerTranslation;

        if (!$this->hasExistingBannerTranslation()) {
            $this->locale = $locale;

            return;
        }

        $this->banner = $this->bannerTranslationEntity->getBanner();
        $this->locale = $this->bannerTranslationEntity->getLocale();
        $this->title = $this->bannerTranslationEntity->getTitle();
        $this->subTitle = $this->bannerTranslationEntity->getSubTitle();
        $this->linkToUrl = $this->bannerTranslationEntity->getLinkToUrl();
        $this->hasLinkToUrl = $this->bannerTranslationEntity->hasLinkToUrl();
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }

    public function getBannerTranslationEntity(): BannerTranslation
    {
        return $this->bannerTranslationEntity;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function hasExistingBannerTranslation(): bool
    {
        return $this->bannerTranslationEntity instanceof BannerTranslation;
    }

    public function setBanner(Banner $banner): void
    {
        $this->banner = $banner;
    }

    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }
}
