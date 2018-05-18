<?php

namespace Backend\Modules\Banners\Domain\Banner\Command;

final class ReSequenceBanners
{
    /**
     * @var string[]
     */
    private $ids;

    /**
     * @param string[] $ids
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
