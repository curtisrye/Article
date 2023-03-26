<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Exception;

final class UserNotFound extends \InvalidArgumentException
{
    private const EXCEPTION_CODE = 404;

    public static function create(int $userId): self
    {
        return new self(
            message: sprintf('Unknown user for id %s', $userId),
            code: self::EXCEPTION_CODE,
        );
    }
}