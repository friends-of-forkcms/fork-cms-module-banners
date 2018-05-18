<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Common\Doctrine\ValueObject\AbstractImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class Image extends AbstractImage
{
    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "8M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "err.JPGGIFAndPNGOnly"
     * )
     */
    public $file;

    protected function getUploadDir(): string
    {
        return 'Banners/Banner/Image';
    }
}
