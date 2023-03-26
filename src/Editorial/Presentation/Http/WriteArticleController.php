<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Config\Model\User;
use App\Editorial\Application\Command\WriteArticle\WriteArticle;
use App\Editorial\Application\Command\WriteArticle\WriteArticleHandler;
use App\Editorial\Presentation\Input\WriteArticleInput;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteArticleController
{
    public function __construct(
        private readonly WriteArticleHandler $writeArticleHandler,
        private readonly ValidatorInterface $validator,
        private readonly Security $security
    ){}

    /**
     * @Route("/editorial/article", name="editorial.article.write", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        $input = $this->generateInput($request);

        $errors = $this->validator->validate($input);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $command = new WriteArticle(
            title: $input->title,
            content: $input->content,
            userId: $user->id(),
            releaseDate: null === $input->releaseDate ? null : new \DateTimeImmutable($input->releaseDate),
            status: $input->status
        );

        $this->writeArticleHandler->__invoke($command);

        return new JsonResponse('', Response::HTTP_OK);
    }

    private function generateInput(Request $request): WriteArticleInput
    {
        $data = json_decode($request->getContent(), true);

        return new WriteArticleInput(
          title: $data['title'] ?? null,
          content: $data['content'] ?? null,
          releaseDate: $data['releaseDate'] ?? null,
          status: $data['status']?? null,
        );
    }
}