<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Editorial\Application\Command\EditArticle\EditArticle;
use App\Editorial\Application\Command\EditArticle\EditArticleHandler;
use App\Editorial\Domain\Exception\ArticleNotFound;
use App\Editorial\Infrastructure\Repository\ArticleRepository;
use App\Editorial\Presentation\Input\EditArticleInput;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditArticleController
{
    public function __construct(
        private readonly EditArticleHandler $editArticleHandler,
        private readonly ValidatorInterface $validator,
        private readonly ArticleRepository $articleRepository,
        private readonly Security $security,
    ){}

    /**
     * @Route("/editorial/article/{articleId}", name="editorial.article.edit", methods={"PUT"})
     */
    public function __invoke(int $articleId, Request $request): Response
    {
        try {
            $article = $this->articleRepository->get($articleId);
            if (!$this->security->isGranted('author', $article->user()->id())) {
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
            $this->editArticleHandler->__invoke($command);
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

        return new EditArticleInput(
            title: $data['title'],
            content: $data['content'],
        );
    }
}