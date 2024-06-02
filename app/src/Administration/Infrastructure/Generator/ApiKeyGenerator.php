<?php

declare(strict_types=1);

namespace App\Administration\Infrastructure\Generator;

use App\Administration\Domain\Generator\ApiKeyGenerator as ApiKeyGeneratorInterface;

class ApiKeyGenerator implements ApiKeyGeneratorInterface
{
    private int $nbBytes;

    public function __construct(int $nbBytes = 32)
    {
        $this->nbBytes = $nbBytes;
    }

    public function generate(): string
    {
        return bin2hex(random_bytes($this->nbBytes));
    }
}