<?php

declare(strict_types=1);

namespace App\Editorial\Infrastructure\Repository;

use App\Core\Domain\Logger\TraceLogger;
use App\Core\Domain\Model\Trace;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Domain\Model\Article;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Model\Tag;
use App\Editorial\Domain\Repository\ArticleRepository as ArticleRepositoryInterface;
use App\Editorial\Domain\Repository\UserRepository;
use Doctrine\DBAL\Connection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly UserRepository $userRepository,
        private readonly TagRepository $tagRepository,
        private readonly TraceLogger $logger
    ) {}

    public function get(int $articleId): Article
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM article 
            WHERE id = :articleId
        SQL;

        $data = $this->connection->executeQuery($sql, ['articleId' => $articleId])->fetchAssociative();
        if (!$data) {
            throw ArticleNotFound::create($articleId);
        }

        return $this->createArticleModel($data);
    }

    public function getActive(int $articleId): Article
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM article 
            WHERE id = :articleId
            AND status <> :deletedStatus
        SQL;

        $data = $this->connection->executeQuery($sql, ['articleId' => $articleId, 'deletedStatus' => Status::DELETED])->fetchAssociative();
        if (!$data) {
            throw ArticleNotFound::create($articleId);
        }

        return $this->createArticleModel($data);
    }

    public function save(Article $article): void
    {
        if (null === $article->id()) {
            $this->connection->insert(
                'article',
                [
                    'title' => $article->title(),
                    'content' => $article->content(),
                    'userId' => $article->user()->id(),
                    'releaseDate' => $article->releaseDate()?->format(DATE_ATOM),
                    'status' => $article->status(),
                ]
            );

            $tagIds = [];
            foreach ($article->tags() as $tag) {
                $tagIds[] = $tag->id();

                $this->connection->insert(
                    'article_tag',
                    ['articleId' => $article->id(), 'tagId' => $tag->id(),]
                );
                $this->connection->update('tag',
                    ['counter' => $tag->counter() + 1,],
                    ['id' => $tag->id()]
                );
            }

            $this->logger->log(new Trace(
                modelName: Article::class,
                oldFields: [],
                newFields: [
                    'title' => $article->title(),
                    'content' => $article->content(),
                    'userId' => $article->user()->id(),
                    'releaseDate' => $article->releaseDate()?->format(DATE_ATOM),
                    'status' => $article->status(),
                    'tags' => $tagIds
                ],
                updatedAt: new \DateTimeImmutable(),
                updatedBy: $article->user()->id(),
            ));
            $this->logArticleChange(null, $article);

            return;
        }

        $oldArticle = $this->get($article->id());
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

        $oldTags = $this->tagRepository->getTagsFromArticleId($article->id());
        $newTags = $article->tags();


        $tagsToAdd = [];
        foreach ($newTags as $tag) {
            if (false === in_array($tag, $oldTags)) {
                $tagsToAdd[] = $tag;
            }
        }
        $tagsToRemove = [];
        foreach ($oldTags as $tag) {
            if (false === in_array($tag, $newTags)) {
                $tagsToRemove[] = $tag;
            }
        }

        foreach ($tagsToRemove as $tag) {
            $this->connection->delete(
                'article_tag',
                ['articleId' => $article->id(), 'tagId' => $tag->id()]
            );
            $this->connection->update('tag',
                ['counter' => $tag->counter() - 1,],
                ['id' => $tag->id()]);
        }

        foreach ($tagsToAdd as $tag) {
            $this->connection->insert(
                'article_tag',
                ['articleId' => $article->id(), 'tagId' => $tag->id()]
            );
            $this->connection->update('tag',
                ['counter' => $tag->counter() + 1,],
                ['id' => $tag->id()]);
        }

        $this->logArticleChange($oldArticle, $article);
    }

    public function createArticleModel(array $data): Article
    {
        $user = $this->userRepository->get((int) $data['userId']);

        $releaseDate = $data['releaseDate'];
        if (null !== $releaseDate) {
            $releaseDate = new \DateTimeImmutable($releaseDate);
        }
        $tags = $this->tagRepository->getTagsFromArticleId($data['id']);

        /** @var array<string|int> $data */
        return Article::create(
            id: (int) $data['id'],
            title: (string) $data['title'],
            content: (string) $data['content'],
            user: $user,
            releaseDate: $releaseDate,
            status: (string) $data['status'],
            tags: $tags
        );
    }

    private function logArticleChange(?Article $oldArticle, Article $newArticle): void
    {
        $oldFields = [];
        if ($oldArticle instanceof Article) {
            $oldFields = [
                'title' => $oldArticle->title(),
                'content' => $oldArticle->content(),
                'userId' => $oldArticle->user()->id(),
                'releaseDate' => $oldArticle->releaseDate()?->format(DATE_ATOM),
                'status' => $oldArticle->status(),
                'tags' => array_map(fn (Tag $tag) => $tag->id(), $oldArticle->tags())
            ];
        }

        $this->logger->log(new Trace(
            modelName: Article::class,
            oldFields: $oldFields,
            newFields: [
                'title' => $newArticle->title(),
                'content' => $newArticle->content(),
                'userId' => $newArticle->user()->id(),
                'releaseDate' => $newArticle->releaseDate()?->format(DATE_ATOM),
                'status' => $newArticle->status(),
                'tags' => array_map(fn (Tag $tag) => $tag->id(), $newArticle->tags())
            ],
            updatedAt: new \DateTimeImmutable(),
            updatedBy: $oldArticle->user()->id(),
        ));
    }
}