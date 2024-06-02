<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Exception;

final class ArticleNotFound extends \InvalidArgumentException
{
    private const EXCEPTION_CODE = 404;

    public static function create(int $articleId): self
    {
        return new self(
            message: sprintf('Unknown article for id %s', $articleId),
            code: self::EXCEPTION_CODE,
        );
    }
}