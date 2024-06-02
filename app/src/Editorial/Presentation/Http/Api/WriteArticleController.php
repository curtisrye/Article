<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Core\Domain\Model\User;
use App\Editorial\Application\Command\WriteArticle\WriteArticle;
use App\Editorial\Presentation\Input\WriteArticleInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteArticleController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ValidatorInterface $validator,
    ){}

    #[Route("/api/v1/editorial/article", name: "api.editorial.article.write", methods: ["POST"])]
    public function __invoke(Request $request): Response
    {
        $input = $this->generateInput($request);

        $errors = $this->validator->validate($input);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        $command = new WriteArticle(
            title: $input->title,
            content: $input->content,
            userId: $user->getId(),
            releaseDate: null === $input->releaseDate ? null : new \DateTimeImmutable($input->releaseDate),
            status: $input->status
        );

        $this->commandBus->handle($command);

        return new JsonResponse('', Response::HTTP_OK);
    }

    private function generateInput(Request $request): WriteArticleInput
    {
        $data = json_decode($request->getContent(), true);

        $article = new WriteArticleInput();
        $article->title = $data['title'] ?? null;
        $article->content = $data['content'] ?? null;
        $article->releaseDate = $data['releaseDate'] ?? null;
        $article->status = $data['status']?? null;

        return $article;
    }
}