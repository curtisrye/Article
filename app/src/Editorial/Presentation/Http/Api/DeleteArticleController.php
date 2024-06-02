<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\DeleteArticle\DeleteArticle;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteArticleController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ArticleRepository $articleRepository,
    ){}

    #[Route("/api/v1/editorial/article/{articleId}", name: "editorial.article.delete", methods: ["DELETE"])]
    public function __invoke(int $articleId): Response
    {
        try {
            $article = $this->articleRepository->get($articleId);
            if (!$this->isGranted('author', $article->id())) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            $command = new DeleteArticle($articleId);
            $this->commandBus->handle($command);

            $this->addFlash('success', 'Your article has been deleted.');
        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }

        return new Response(content: '', status: Response::HTTP_OK);
    }
}