<?php

declare(strict_types=1);

namespace App\Administration\Domain\Generator;

interface ApiKeyGenerator
{
    public function generate(): string;
}