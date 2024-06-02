<?php

declare(strict_types=1);

namespace App\Editorial\Application\Exception;

final class StatusNotAllowed extends \InvalidArgumentException
{
    private const EXCEPTION_CODE = 400;

    public static function create(string $errorStatus): self
    {
        return new self(
            message: sprintf(format: 'Unknown status %s for article creation', values: $errorStatus),
            code: self::EXCEPTION_CODE,
        );
    }
}