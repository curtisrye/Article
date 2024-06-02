<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Exception;

final class TagNotFound extends \InvalidArgumentException
{
    private const EXCEPTION_CODE = 404;

    public static function create(int $tagId): self
    {
        return new self(
            message: sprintf('Unknown tag for id %s', $tagId),
            code: self::EXCEPTION_CODE,
        );
    }
}