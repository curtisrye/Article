<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\ConvertToDraft\ConvertToDraft;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use App\Editorial\Presentation\Input\ConvertToDraftInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConvertToDraftController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ValidatorInterface $validator,
        private readonly ArticleRepository $articleRepository,
    ){}

    #[Route("/api/v1/editorial/article/{articleId}/convert-to-draft", name: "editorial.article.convert_to_draft", methods: ["POST"])]
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

                return new Response($errorsString, Response::HTTP_BAD_REQUEST);
            }

            $command = new ConvertToDraft(
                articleId: $articleId,
                releaseDate: null === $input->releaseDate ? null : new \DateTimeImmutable($input->releaseDate),
            );
            $this->commandBus->handle($command);

            $this->addFlash('success', 'Your article has been updated.');
        } catch (ArticleNotFound $articleNotFound) {
            return new Response(
                content: $articleNotFound->getMessage(),
                status: $articleNotFound->getCode()
            );
        }

        return new Response(content: '', status: Response::HTTP_OK);
    }

    private function generateInput(Request $request): ConvertToDraftInput
    {
        $data = json_decode($request->getContent(), true);

        return new ConvertToDraftInput(
            releaseDate: $data['releaseDate'] ?? null,
        );
    }
}