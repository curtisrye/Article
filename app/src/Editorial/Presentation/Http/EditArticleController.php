<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\EditArticle\EditArticle;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Presentation\Form\EditArticleFormType;
use App\Editorial\Presentation\Input\EditArticleInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EditArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly CommandBus        $commandBus,
    ) {}

    #[Route('/editorial/article/{identifier}/edit', name: 'editorial.article.edit')]
    #[IsGranted('author', 'identifier')]
    public function __invoke(int $identifier, Request $request): Response
    {
        $article = $this->articleRepository->get($identifier);

        $input = new EditArticleInput();
        $input->title = $article->title();
        $input->content = $article->content();

        $form = $this->createForm(EditArticleFormType::class, $input);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new EditArticle(
                $identifier,
                $input->title,
                $input->content,
            );
            $this->commandBus->handle($command);
            $this->addFlash('success', 'Your article has been saved.');

            return $this->redirect('/editorial/article');
        }

        return $this->render('editorial/edit-article.html.twig', [
            'editArticleForm' => $form->createView(),
        ]);
    }
}