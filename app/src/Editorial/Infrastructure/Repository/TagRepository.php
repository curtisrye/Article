<?php

declare(strict_types=1);

namespace App\Editorial\Infrastructure\Repository;

use App\Editorial\Domain\Exception\TagNotFound;
use App\Editorial\Domain\Model\Tag;
use App\Editorial\Domain\Repository\TagRepository as TagRepositoryInterface;
use Doctrine\DBAL\Connection;

class TagRepository implements TagRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {}

    public function get(int $id): Tag
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM tag 
            WHERE id = :tagId
        SQL;

        $data = $this->connection->executeQuery($sql, ['tagId' => $id])->fetchAssociative();
        if (!$data) {
            throw TagNotFound::create($id);
        }

        return new Tag($id, $data['name'], $data['counter']);
    }

    public function getTagsFromArticleId(int $articleId): array
    {
        $sql = <<< 'SQL'
                SELECT t.*
                FROM article_tag AS article_tag
                INNER JOIN tag AS t ON t.id = article_tag.tagId
                WHERE articleId = :articleId
                
            SQL;
        $data = $this->connection->executeQuery($sql, ['articleId' => $articleId])->fetchAllAssociative();
        $tags = [];

        foreach ($data as $rawTag) {
            $tags[] = new Tag($rawTag['id'], $rawTag['name'], $rawTag['counter']);
        }

        return $tags;
    }

    public function save(Tag $tag): void
    {
        if(null === $tag->id()) {
            $this->connection->insert('tag', [
                'name' => $tag->name(),
                'counter' => $tag->counter(),
            ]);

            return;
        }

        $this->connection->update('tag', [
            'name' => $tag->name(),
        ], [
            'id' => $tag->id(),
        ]);
    }
}