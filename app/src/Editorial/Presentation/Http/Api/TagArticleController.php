<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\TagArticle\TagAnArticle;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Domain\Exception\TagNotFound;
use App\Editorial\Domain\Repository\TagRepository;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagArticleController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ArticleRepository $articleRepository,
        private readonly TagRepository $tagRepository
    ){}

    #[Route("/api/v1/editorial/tag/{tagId}/article/{articleId}", name: "editorial.tag.article", methods: ["POST"])]
    public function __invoke(int $articleId, int $tagId, Request $request): Response
    {
        try {
            $this->articleRepository->get($articleId);
            $this->tagRepository->get($tagId);

            if (!$this->isGranted('author', $articleId)) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            $command = new TagAnArticle(
                tagId: $tagId,
                articleId: $articleId
            );
            $this->commandBus->handle($command);
        } catch (ArticleNotFound | TagNotFound $notFound) {
            return new Response(
                content: $notFound->getMessage(),
                status: $notFound->getCode()
            );
        }

        return new Response(content: '', status: Response::HTTP_OK);
    }
}