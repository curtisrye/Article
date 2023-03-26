<?php

declare(strict_types=1);

namespace App\Editorial\Infrastructure\Finder;

use App\Editorial\Application\Query\GetArticles\RetrieveArticles;
use App\Editorial\Domain\Finder\ArticleFinder as ArticleFinderInterface;
use App\Editorial\Domain\Model\Article;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Repository\UserRepository;
use App\Editorial\Domain\ValueObject\PaginatedArticleResults;
use Doctrine\DBAL\Connection;

class ArticleFinder implements ArticleFinderInterface
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly Connection             $connection,
    ){}

    public function retrieveArticles(RetrieveArticles $query): PaginatedArticleResults
    {
        $sql = <<< 'SQL'
            SELECT *
            FROM article 
            WHERE userId = :userId
            AND status <> :deletedStatus
            
        SQL;

        $parameters = ['userId' => $query->userId(), 'deletedStatus' => Status::DELETED];
        $sql = $this->filterByStatus($query, $sql);

        $sql = $this->addPagination($query, $sql);

        $stmt = $this->connection->prepare($sql);
        $data = $stmt->executeQuery($parameters)->fetchAllAssociative();
        $user = $this->userRepository->get($query->userId());

        $results = [];
        foreach ($data as $rawArticle) {

            $releaseDate = $rawArticle['releaseDate'];
            if (is_string($releaseDate)) {
                $releaseDate = new \DateTimeImmutable($releaseDate);
            }

            /** @var array<string|int> $data */
            $article = Article::create(
                id: (int) $rawArticle['id'],
                title: (string) $rawArticle['title'],
                content: (string) $rawArticle['content'],
                user: $user,
                releaseDate: $releaseDate,
                status: (string) $rawArticle['status'],
            );

            $results[] = $article;
        }

        $totalResult = $this->countTotalResultForRetrieveArticles($query);

        return new PaginatedArticleResults(totalResult: $totalResult, items: $results);
    }

    private function countTotalResultForRetrieveArticles(RetrieveArticles $query): int
    {
        $sql = <<< 'SQL'
            SELECT COUNT(*)
            FROM article 
            WHERE userId = :userId
            AND status <> :deletedStatus
            
        SQL;

        $parameters = ['userId' => $query->userId(), 'deletedStatus' => Status::DELETED];
        $sql = $this->filterByStatus($query, $sql);

        $stmt = $this->connection->prepare($sql);

        return $stmt->executeQuery($parameters)->fetchOne();
    }

    private function filterByStatus(RetrieveArticles $query, string $sql): string
    {
        if (empty($query->status())) {
            return $sql;
        }
        $searchStatus = [];
        foreach ($query->status() as $status) {
            $searchStatus[] = sprintf("'%s'", $status);
        }

        $sql .= sprintf(<<< 'SQL'
         AND status IN (%s)
        SQL, implode(',', $searchStatus));

        return $sql;
    }

    private function addPagination(RetrieveArticles $query, string $sql): string
    {
        $paginator = $query->paginator();

        if (null === $paginator) {
            return $sql;
        }

        if ($paginator->getItemsPerPage()) {
            $sql .= sprintf('LIMIT %d ', $paginator->getItemsPerPage());
            if ($paginator->getPage()) {
                $sql .= sprintf('OFFSET %d ', $paginator->getFirstResultOffset());
            }
        }

        return $sql;
    }
}