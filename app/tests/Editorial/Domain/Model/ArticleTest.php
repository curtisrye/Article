<?php

declare(strict_types=1);

namespace App\Tests\Editorial\Domain\Model;

use App\Editorial\Domain\Exception\InvalidArticleEdition;
use App\Editorial\Domain\Model\Article;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webmozart\Assert\InvalidArgumentException;

class ArticleTest extends KernelTestCase
{
    public function testCreate(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = 'deleted';

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $this->assertEquals($id, $article->id());
        $this->assertEquals($title, $article->title());
        $this->assertEquals($content, $article->content());
        $this->assertEquals($status, $article->status());
        $this->assertSame($user, $article->user());
        $this->assertEquals($releaseDate, $article->releaseDate());
    }

    public function testCreateWithWrongStatus(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = 'bad status';

        $this->expectException(InvalidArgumentException::class);

        Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );
    }

    public function testCreateWithWrongTitle(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fetttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt
        ttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = 'draft';

        $this->expectException(InvalidArgumentException::class);

        Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );
    }

    public function testCreateValidDraftArticle(): void
    {
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');

        $article = Article::createDraftArticle(
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate
        );

        $this->assertEquals(Status::DRAFT, $article->status());
    }

    public function testCreateInValidDraftArticle(): void
    {
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('-1 day');

        $this->expectException(InvalidArgumentException::class);

        Article::createDraftArticle(
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate
        );
    }

    public function testCreatePublishedArticle(): void
    {
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');

        $article = Article::createPublishedArticle(
            title: $title,
            content: $content,
            user: $user,
        );

        $this->assertEquals(Status::PUBLISHED, $article->status());
    }

    public function testEdit(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DRAFT;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $newTitle = 'Le livre de Boba Fett by Boba Fett';
        $newContent = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers. Car c\'est impossible.';

        $article->edit(title: $newTitle, content: $newContent);

        $this->assertEquals($newTitle, $article->title());
        $this->assertEquals($newContent, $article->content());
    }

    public function testEditPublishedArticle(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::PUBLISHED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $newTitle = 'Le livre de Boba Fett by Boba Fett';
        $newContent = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers. Car c\'est impossible.';

        $this->expectException(InvalidArticleEdition::class);

        $article->edit(title: $newTitle, content: $newContent);
    }

    public function testEditDeletedArticle(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DELETED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $newTitle = 'Le livre de Boba Fett by Boba Fett';
        $newContent = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers. Car c\'est impossible.';

        $this->expectException(InvalidArticleEdition::class);

        $article->edit(title: $newTitle, content: $newContent);
    }

    public function testConvertToDraft(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::PUBLISHED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $article->convertToDraft(null);

        $this->assertEquals(Status::DRAFT, $article->status());
    }

    public function testConvertToDraftDeletedArticle(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DELETED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $this->expectException(InvalidArticleEdition::class);

        $article->convertToDraft(null);
    }

    public function testConvertToDraftWithWrongReleaseDate(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::PUBLISHED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $newReleaseDate = new \DateTimeImmutable('-1 day');

        $this->expectException(InvalidArgumentException::class);

        $article->convertToDraft($newReleaseDate);
    }

    public function testPublish(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DRAFT;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $article->publish();

        $this->assertEquals(Status::PUBLISHED, $article->status());
    }

    public function testPublishDeletedArticle(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DELETED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $this->expectException(InvalidArticleEdition::class);

        $article->publish();
    }

    public function testDelete(): void
    {
        $id = 1;
        $title = 'Le livre de Boba Fett';
        $content = 'Ce livre est à prendre au second degré, il n\'existe pas encore de chasseur de prime parcourant l\'univers.';
        $user = User::create(1,'Han', 'Solo');
        $releaseDate = new \DateTimeImmutable('+1 day');
        $status = Status::DELETED;

        $article = Article::create(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status
        );

        $article->delete();

        $this->assertEquals(Status::DELETED, $article->status());
    }
}