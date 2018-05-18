<?php

namespace Backend\Modules\Banners\Domain\Banner\Exception;

class BannerNotFound extends \Exception
{
    public static function forEmptyId(): \Exception
    {
        return new \Exception('The id you have given is null');
    }

    public static function forEmptyURL(): \Exception
    {
        return new \Exception('The given URL for a banner is empty.');
    }

    public static function forId($id): \Exception
    {
        return new \Exception('A banner with id = "' . $id . '"" can\'t be found');
    }

    public static function forURL($URL): \Exception
    {
        return new \Exception('The given URL "' . $URL . '" for a banner is not found.');
    }
}
