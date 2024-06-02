<?php

declare(strict_types=1);

namespace App\Consulting\Infrastructure\Finder;

use App\Consulting\Domain\Finder\ArticleFinder as ArticleFinderInterface;
use App\Consulting\Domain\Model\Article;
use App\Consulting\Domain\Model\User;
use App\Editorial\Domain\Model\Status;
use Doctrine\DBAL\Connection;

class ArticleFinder implements ArticleFinderInterface
{
    public function __construct(
        private readonly Connection $connection,
    ){}

    public function findAll(): array
    {
        $sql = <<< 'SQL'
            SELECT a.id as id,
                   a.title as title,
                   a.content as content,
                   a.userId as userId,
                   a.releaseDate as releaseDate,
                   u.firstname as userFirstName,
                   u.lastname as userLastName
            FROM article a
            JOIN user u ON a.userId = u.id
            WHERE status = :published
            ORDER BY releaseDate DESC
        SQL;
        $data = $this->connection->executeQuery($sql, ['published' => Status::PUBLISHED])->fetchAllAssociative();

        $results = [];
        foreach ($data as $rawArticle) {

            $releaseDate = $rawArticle['releaseDate'];
            if (is_string($releaseDate)) {
                $releaseDate = new \DateTimeImmutable($releaseDate);
            }

            /** @var array<string|int> $data */
            $article = new Article(
                id: (int) $rawArticle['id'],
                title: (string) $rawArticle['title'],
                content: (string) $rawArticle['content'],
                user: new User($rawArticle['userId'], $rawArticle['userFirstName'], $rawArticle['userLastName']),
                releaseDate: $releaseDate,
            );

            $results[] = $article;
        }

        return $results;
    }
}