<?php

namespace Backend\Modules\Banners\Domain\Banner;

final class Status
{
    const ACTIVE = 'active';
    const HIDDEN = 'hidden';

    /** @var string */
    private $status;

    private function __construct(string $status)
    {
        if (!in_array($status, self::getPossibleValues(), true)) {
            throw new \InvalidArgumentException('Invalid value');
        }

        $this->status = $status;
    }

    public static function fromString(string $status): Status
    {
        return new self($status);
    }

    public function __toString(): string
    {
        return (string) $this->status;
    }

    public function equals(Status $status): bool
    {
        if (!($status instanceof $this)) {
            return false;
        }

        return $status == $this;
    }

    public static function getPossibleValues(): array
    {
        return [
            self::ACTIVE,
            self::HIDDEN,
        ];
    }

    public static function active(): Status
    {
        return new self(self::ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->equals(self::active());
    }

    public static function hidden(): Status
    {
        return new self(self::HIDDEN);
    }

    public function isHidden(): bool
    {
        return $this->equals(self::hidden());
    }
}
