<?php

declare(strict_types=1);

namespace App\Administration\Domain\Exception;

final class UserNotFound extends \InvalidArgumentException
{
    private const EXCEPTION_CODE = 404;

    public static function createByUserName(string $userName): self
    {
        return new self(
            message: sprintf('Unknown user for userName %s', $userName),
            code: self::EXCEPTION_CODE,
        );
    }
}