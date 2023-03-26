<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Model;

final class Status
{
    public const DRAFT = 'draft';

    public const PUBLISHED = 'published';

    public const DELETED = 'deleted';

    public const ALLOWED_STATUS = [self::DRAFT, self::PUBLISHED];

    public const ALL = [self::DRAFT, self::PUBLISHED, self::DELETED];
}