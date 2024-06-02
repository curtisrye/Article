<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Exception;

final class InvalidArticleEdition extends \LogicException
{
    private const EXCEPTION_CODE = 500;

    public static function cannotBeEditedFromStatus(string $status): self
    {
        return new self(
            message: sprintf('Article cannot be edited from status "%s"', $status),
            code: self::EXCEPTION_CODE,
        );
    }

    public static function cannotBeEditedFromDeletedStatus(): self
    {
        return new self(
            message: 'Cannot edit deleted article',
            code: self::EXCEPTION_CODE,
        );
    }
}