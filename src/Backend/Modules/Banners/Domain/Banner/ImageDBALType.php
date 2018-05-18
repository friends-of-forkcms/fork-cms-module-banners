<?php

namespace Backend\Modules\Banners\Domain\Banner;

use Common\Doctrine\Type\AbstractImageType;
use Common\Doctrine\ValueObject\AbstractImage;

final class ImageDBALType extends AbstractImageType
{
    const NAME = 'banners_banner_image';

    protected function createFromString(string $image): AbstractImage
    {
        return Image::fromString($image);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
