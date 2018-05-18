<?php

namespace Backend\Modules\Banners\Domain\BannerTranslation\Exception;

use Common\Locale;

class BannerTranslationNotFound extends \Exception
{
    public static function forLocale(Locale $locale): \Exception
    {
        return new \Exception('The translation can\'t be found for "' . (string) $locale . '"');
    }
}
