<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Editorial\Application\Command\PublishArticle\PublishArticle;
use App\Editorial\Application\Command\PublishArticle\PublishArticleHandler;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishArticleController
{
    public function __construct(
        private readonly PublishArticleHandler $publishArticleHandler,
        private readonly ArticleRepository $articleRepository,
        private readonly Security $security,
    ){}

    /**
     * @Route("/editorial/article/{articleId}/publish", name="editorial.article.publish", methods={"POST"})
     */
    public function __invoke(int $articleId): Response
    {
        try {
            $article = $this->articleRepository->get($articleId);
            if (!$this->security->isGranted('author', $article->user()->id())) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            $command = new PublishArticle($articleId);
            $this->publishArticleHandler->__invoke($command);
        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }

        return new Response(content: '', status: Response::HTTP_OK);
    }
}