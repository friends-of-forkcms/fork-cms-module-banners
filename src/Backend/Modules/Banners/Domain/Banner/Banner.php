<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Common\Locale;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslation;
use Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslationDataTransferObject;
use Backend\Modules\Banners\Domain\BannerTranslation\Exception\BannerTranslationNotFound;
use Common\Doctrine\Entity\Meta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BannerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Banner
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $sequence;

    /**
     * @var Status
     *
     * @ORM\Column(type="banners_banner_status", options={"default" = "active"})
     */
    private $status;

    /**
     * @var Image
     *
     * @ORM\Column(type="banners_banner_image")
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $editedOn;

    /**
     * @var ArrayCollection|BannerTranslation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="\Backend\Modules\Banners\Domain\BannerTranslation\BannerTranslation",
     *     mappedBy="banner",
     *     orphanRemoval=true,
     *     cascade={"persist"},
     *     fetch="EAGER"
     * )
     */
    private $translations;

    private function __construct(
        Image $image,
        int $sequence,
        Status $status
    ) {
        $this->image = $image;
        $this->sequence = $sequence;
        $this->status = $status;
        $this->translations = new ArrayCollection();
    }

    /**
     * @param BannerDataTransferObject $bannerDataTransferObject
     * @param integer $newSequence
     * @return Banner
     */
    public static function fromDataTransferObject(
        BannerDataTransferObject $bannerDataTransferObject,
        int $newSequence = null
    ): Banner {
        if ($bannerDataTransferObject->hasExistingBanner()) {
            /** @var Banner $banner */
            $banner = $bannerDataTransferObject->getBannerEntity();

            $banner->update(
                Status::fromString($bannerDataTransferObject->status),
                $bannerDataTransferObject->image
            );
            $banner->translations = $bannerDataTransferObject->translations->map(
                function (BannerTranslationDataTransferObject $bannerTranslationDataTransferObject) use ($banner) {
                    $bannerTranslationDataTransferObject->setBanner($banner);
                    return BannerTranslation::fromDataTransferObject($bannerTranslationDataTransferObject);
                }
            );

            return $banner;
        }

        $banner = new self(
            $bannerDataTransferObject->image,
            $newSequence,
            Status::fromString($bannerDataTransferObject->status)
        );
        $banner->translations = $bannerDataTransferObject->translations->map(
            function (BannerTranslationDataTransferObject $bannerTranslationDataTransferObject) use ($banner) {
                $bannerTranslationDataTransferObject->setBanner($banner);
                return BannerTranslation::fromDataTransferObject($bannerTranslationDataTransferObject);
            }
        );

        return $banner;
    }

    public function update(
        Status $status,
        Image $image
    ): void {
        $this->status = $status;
        $this->image = $image;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function prepareToUploadImage()
    {
        $this->image->prepareToUpload();
    }

    /**
     * @ORM\PostUpdate()
     * @ORM\PostPersist()
     */
    public function uploadImage()
    {
        $this->image->upload();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeImage()
    {
        $this->image->remove();
    }

    public function setSequence(int $sequence): void
    {
        $this->sequence = $sequence;
    }

    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    public function getEditedOn(): \DateTime
    {
        return $this->editedOn;
    }

    /**
     * @return BannerTranslation[]|Collection
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    /**
     * @param Locale $locale
     * @return BannerTranslation when the translation does not exist
     * @throws \Exception
     */
    public function getTranslation(Locale $locale): BannerTranslation
    {
        if ($this->translations->isEmpty()) {
            throw BannerTranslationNotFound::forLocale($locale);
        }

        $translations = $this->translations->filter(
            function (BannerTranslation $translation) use ($locale) {
                return $translation->getLocale()->equals($locale);
            }
        );

        if ($translations->isEmpty()) {
            throw BannerTranslationNotFound::forLocale($locale);
        }

        return $translations->first();
    }

    public function addTranslation(BannerTranslation $translation): void
    {
        if ($this->translations->contains($translation)) {
            return;
        }

        $this->translations->add($translation);
    }

    public function removeTranslation(BannerTranslation $translation): void
    {
        $this->translations->removeElement($translation);
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdOn = $this->editedOn = new \Datetime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->editedOn = new \Datetime();
    }
}
