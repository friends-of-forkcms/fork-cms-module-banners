<?php

namespace Backend\Modules\Banners\Domain\BannerTranslation;

use Backend\Modules\Banners\Domain\Banner\Banner;
use Common\Doctrine\Entity\Meta;
use Common\Locale;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslationRepository")
 */
class BannerTranslation
{
    /**
     * @var Locale
     *
     * @ORM\Id()
     * @ORM\Column(type="locale", name="locale")
     */
    private $locale;

    /**
     * @var Banner
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Backend\Modules\Banners\Domain\Banner\Banner", inversedBy="translations")
     * @ORM\JoinColumn(name="bannerId", referencedColumnName="id")
     */
    private $banner;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $linkToUrl;

    public function __construct(
        Locale $locale,
        Banner $banner,
        string $title,
        ?string $subTitle,
        ?string $linkToUrl
    ) {
        $this->locale = $locale;
        $this->banner = $banner;

        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->linkToUrl = $linkToUrl;

        $this->banner->addTranslation($this);
    }

    public static function fromDataTransferObject(
        BannerTranslationDataTransferObject $bannerTranslationDataTransferObject
    ): BannerTranslation {
        // Update the BannerTranslation
        if ($bannerTranslationDataTransferObject->hasExistingBannerTranslation()) {
            $bannerTranslation = $bannerTranslationDataTransferObject->getBannerTranslationEntity();

            $bannerTranslation->title = $bannerTranslationDataTransferObject->title;
            $bannerTranslation->subTitle = $bannerTranslationDataTransferObject->subTitle;
            $bannerTranslation->linkToUrl = ($bannerTranslationDataTransferObject->hasLinkToUrl)
                ? self::stripLinkToUrl($bannerTranslationDataTransferObject->linkToUrl) : null;

            return $bannerTranslation;
        }

        // Create new BannerTranslation
        $bannerTranslation = new self(
            $bannerTranslationDataTransferObject->getLocale(),
            $bannerTranslationDataTransferObject->getBanner(),
            $bannerTranslationDataTransferObject->title,
            $bannerTranslationDataTransferObject->subTitle,
            self::stripLinkToUrl($bannerTranslationDataTransferObject->linkToUrl)
        );

        return $bannerTranslation;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }

    public function getLinkToUrl(): ?string
    {
        return $this->linkToUrl;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function hasSubTitle(): bool
    {
        return $this->subTitle !== null;
    }

    public function hasLinkToUrl(): bool
    {
        return $this->linkToUrl !== null;
    }

    public static function stripLinkToUrl(?string $link): ?string
    {
        if ($link === null) {
            return null;
        }

        $link = str_replace(SITE_URL, '', $link);
        $link = ltrim($link, '/');

        return $link;
    }

    public function getDataTransferObject(): BannerTranslationDataTransferObject
    {
        $dataTransferObject = new BannerTranslationDataTransferObject($this, $this->locale);
        $dataTransferObject->title = $this->title;
        $dataTransferObject->subTitle = $this->subTitle;

        return $dataTransferObject;
    }
}
