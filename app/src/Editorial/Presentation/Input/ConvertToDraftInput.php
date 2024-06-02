<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ConvertToDraftInput
{
    public function __construct(
        public $releaseDate,
    )
    {}

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata
            ->addPropertyConstraints(
                property: 'releaseDate',
                constraints: [new Assert\DateTime(), new Assert\LessThan('today')]
            );
    }
}