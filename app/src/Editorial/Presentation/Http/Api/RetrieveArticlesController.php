<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Model\User;
use App\Editorial\Application\Paginator\Paginator;
use App\Editorial\Application\Query\GetArticles\RetrieveArticles;
use App\Editorial\Domain\Finder\ArticleFinder;
use App\Editorial\Presentation\Input\RetrieveArticlesInput;
use App\Editorial\Presentation\ViewModel\ArticleCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RetrieveArticlesController extends AbstractController
{
    public function __construct(
        private readonly ArticleFinder $articleFinder,
        private readonly ValidatorInterface $validator,
    ){}

    #[Route("/api/v1/editorial/article", name: "editorial.article.collection.get", methods: ["GET"])]
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $input = $this->generateInput($request);

        $errors = $this->validator->validate($input);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString, Response::HTTP_BAD_REQUEST);
        }

        $paginator = null;
        if (null !== $input->page && null !== $input->itemPerPage) {
            $paginator = new Paginator(
                page: $input->page,
                itemsPerPage: $input->itemPerPage
            );
        }

        $query = new RetrieveArticles(
            userId: $user->getId(),
            status: $input->status ?? [],
            paginator: $paginator,
        );
        $result = $this->articleFinder->retrieveArticles($query);

        $view = new ArticleCollection($paginator, $result);

        return new JsonResponse($view, Response::HTTP_OK);
    }

    private function generateInput(Request $request): RetrieveArticlesInput
    {
        $data = json_decode($request->getContent(), true);

        return new RetrieveArticlesInput(
            status: $data['status'] ?? null,
            page: $data['page'] ?? null,
            itemPerPage: $data['itemPerPage'] ?? null,
        );
    }
}