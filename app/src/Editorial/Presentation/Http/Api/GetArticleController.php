<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Presentation\ViewModel\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ){}

    #[Route("/api/v1/editorial/article/{articleId}", name: "editorial.article.get", methods: ["GET"])]
    public function __invoke(int $articleId): Response
    {
        try {
            $article = $this->articleRepository->getActive($articleId);
            if (!$this->isGranted('author', $article->id())) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            return new JsonResponse(Article::fromModel($article), Response::HTTP_OK);

        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }
    }
}