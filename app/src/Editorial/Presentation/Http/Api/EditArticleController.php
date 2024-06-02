<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\EditArticle\EditArticle;
use App\Editorial\Application\Command\EditArticle\EditArticleHandler;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use App\Editorial\Presentation\Input\EditArticleInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditArticleController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ValidatorInterface $validator,
        private readonly ArticleRepository $articleRepository,
    ){}

    #[Route("/api/v1/editorial/article/{articleId}", name: "editorial.article.edit", methods: ["PUT"])]
    public function __invoke(int $articleId, Request $request): Response
    {
        try {
            $article = $this->articleRepository->get($articleId);
            if (!$this->isGranted('author', $article->id())) {
                return new Response(content: 'Access Denied', status: Response::HTTP_FORBIDDEN);
            }

            $input = $this->generateInput($request);
            $errors = $this->validator->validate($input);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new Response(content: $errorsString, status: Response::HTTP_BAD_REQUEST);
            }

            $command = new EditArticle(
                articleId: $articleId,
                title: $input->title,
                content: $input->content,
            );
            $this->commandBus->handle($command);
        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }

        return new Response(content: '', status: Response::HTTP_OK);
    }

    private function generateInput(Request $request): EditArticleInput
    {
        $data = json_decode($request->getContent(), true);

        $input = new EditArticleInput();
        $input->title = $data['title'] ?? null;
        $input->content = $data['content'] ?? null;

        return $input;
    }
}