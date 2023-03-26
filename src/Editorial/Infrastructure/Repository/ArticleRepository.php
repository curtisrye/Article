<?php

declare(strict_types=1);

namespace App\Editorial\Infrastructure\Repository;

use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Domain\Model\Article;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Repository\ArticleRepository as ArticleRepositoryInterface;
use App\Editorial\Domain\Repository\UserRepository;
use Doctrine\DBAL\Connection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private readonly Connection $connection, private readonly UserRepository $userRepository)
    {}

    public function get(int $articleId): Article
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM article 
            WHERE id = :articleId
        SQL;

        $stmt = $this->connection->prepare($sql);
        $data = $stmt->executeQuery(['articleId' => $articleId])->fetchAssociative();
        if (!$data) {
            throw ArticleNotFound::create($articleId);
        }

        return $this->createArticleModel($data, $articleId);
    }

    public function getActive(int $articleId): Article
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM article 
            WHERE id = :articleId
            AND status <> :deletedStatus
        SQL;

        $stmt = $this->connection->prepare($sql);
        $data = $stmt->executeQuery(['articleId' => $articleId, 'deletedStatus' => Status::DELETED])->fetchAssociative();
        if (!$data) {
            throw ArticleNotFound::create($articleId);
        }


        return $this->createArticleModel($data, $articleId);
    }

    public function save(Article $article): void
    {
        if (null === $article->id()) {
            $this->connection->insert(
                'article',
                [
                    'id' => null,
                    'title' => $article->title(),
                    'content' => $article->content(),
                    'userId' => $article->user()->id(),
                    'releaseDate' => $article->releaseDate()?->format(DATE_ATOM),
                    'status' => $article->status(),
                ]
            );

            return;
        }

        $this->connection->update(
            'article',
            [
                'title' => $article->title(),
                'content' => $article->content(),
                'userId' => $article->user()->id(),
                'releaseDate' => $article->releaseDate()?->format(DATE_ATOM),
                'status' => $article->status(),
            ],
            ['id' => $article->id()]
        );
    }

    private function createArticleModel(array $data, int $articleId): Article
    {
        $user = $this->userRepository->get((int) $data['userId']);

        $releaseDate = $data['releaseDate'];
        if (null !== $releaseDate) {
            $releaseDate = new \DateTimeImmutable($releaseDate);
        }

        /** @var array<string|int> $data */
        return Article::create(
            id: (int) $data['id'],
            title: (string) $data['title'],
            content: (string) $data['content'],
            user: $user,
            releaseDate: $releaseDate,
            status: (string) $data['status'],
        );
    }
}