<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Input;

use App\Editorial\Domain\Model\Article;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

final class WriteArticleInput
{
    public $title;
    public $content;
    public $releaseDate;
    public $status;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata
            ->addPropertyConstraints(
                property: 'title',
                constraints: [new Assert\NotBlank(), new Assert\Type('string'), new Assert\Length([
                    'max' => Article::CHARACTER_TITLE_LIMIT,
                    'maxMessage' => 'Your first title cannot be longer than {{ limit }} characters',
                ])]
            )->addPropertyConstraints(
                property: 'content',
                constraints: [new Assert\NotBlank(), new Assert\Type('string')]
            )->addPropertyConstraints(
                property: 'releaseDate',
                constraints: [new Assert\DateTime()]
            );
    }
}