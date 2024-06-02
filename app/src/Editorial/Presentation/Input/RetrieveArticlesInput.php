<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Input;

use App\Editorial\Domain\Model\Status;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class RetrieveArticlesInput
{
    public function __construct(
        public $status,
        public $page,
        public $itemPerPage,
    )
    {}

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata
            ->addPropertyConstraints(
                property: 'status',
                constraints: [
                    new Assert\Type('array'),
                    new Assert\All(
                        new Assert\ExpressionSyntax(
                            ['allowedVariables' => Status::ALLOWED_STATUS,],
                            sprintf('Status not allowed, allowed status is %s', implode(separator: ' or ', array: Status::ALLOWED_STATUS))
                        )
                    )
                ]
            )->addPropertyConstraints(
                property: 'page',
                constraints: [new Assert\Type('integer'), new Assert\GreaterThan(0)]
            )->addPropertyConstraints(
                property: 'itemPerPage',
                constraints: [new Assert\Type('integer'), new Assert\GreaterThan(0)]
            );
    }
}