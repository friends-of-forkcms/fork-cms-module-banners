<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Backend\Core\Language\Language;
use Backend\Core\Language\Locale;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslation;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslationDataTransferObject;
use Common\Doctrine\Entity\Meta;
use Doctrine\Common\Collections\ArrayCollection;

class BannerDataTransferObject
{
    /**
     * @var Banner
     */
    private $bannerEntity;

    /**
     * @var Image
     */
    public $image;

    /**
     * @var string
     */
    public $status;

    /**
     * @var BannerTranslationDataTransferObject[]|ArrayCollection
     */
    public $translations;

    /**
     * BannerDataTransferObject constructor.
     *
     * @param Banner|null $banner
     */
    public function __construct(Banner $banner = null)
    {
        $this->bannerEntity = $banner;
        $this->translations = new ArrayCollection();

        if (!$this->hasExistingBanner()) {
            foreach (array_keys(Language::getWorkingLanguages()) as $workingLanguage) {
                $this->translations->set(
                    $workingLanguage,
                    new BannerTranslationDataTransferObject(
                        null,
                        Locale::fromString($workingLanguage)
                    )
                );
            }

            return;
        }

        $this->image = $this->bannerEntity->getImage();
        $this->status = (string) $this->bannerEntity->getStatus();

        /** @var BannerTranslation $translation */
        foreach ($this->bannerEntity->getTranslations() as $translation) {
            $this->translations->set((string) $translation->getLocale(), $translation->getDataTransferObject());
        }
    }

    public function getBannerEntity(): ?Banner
    {
        return $this->bannerEntity;
    }

    public function hasExistingBanner(): bool
    {
        return $this->bannerEntity instanceof Banner;
    }

    public function setBannerEntity(Banner $banner): void
    {
        $this->bannerEntity = $banner;
    }
}
