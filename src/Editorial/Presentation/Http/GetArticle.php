<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Presentation\ViewModel\Article;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetArticle
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly Security $security,
    ){}

    /**
     * @Route("/editorial/article/{articleId}", name="editorial.article.get", methods={"GET"})
     */
    public function __invoke(int $articleId): Response
    {
        try {
            $article = $this->articleRepository->getActive($articleId);
            if (!$this->security->isGranted('author', $article->user()->id())) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            if ($article->status())

            $view = Article::fromModel($article);

            return new JsonResponse($view, Response::HTTP_OK);

        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }
    }
}