<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http;

use App\Core\Domain\Command\CommandBus;
use App\Core\Domain\Model\User;
use App\Editorial\Application\Command\WriteArticle\WriteArticle;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Presentation\Form\WriteArticleFormType;
use App\Editorial\Presentation\Input\WriteArticleInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WriteArticleController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {}

    #[Route('/editorial/article/write', name: 'editorial.article.write')]
    public function __invoke(Request $request): Response
    {
        $newArticle = new WriteArticleInput();
        $newArticle->releaseDate = null;
        $newArticle->status = Status::DRAFT;

        $form = $this->createForm(WriteArticleFormType::class, $newArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $command = new WriteArticle(
                $newArticle->title,
                $newArticle->content,
                $user->getId(),
                $newArticle->releaseDate,
                $newArticle->status,
            );
            $this->commandBus->handle($command);
            $this->addFlash('success', 'Your article has been saved.');

            return $this->redirect('/editorial/article');
        }

        return $this->render('editorial/write-article.html.twig', [
            'writeArticleForm' => $form->createView(),
        ]);
    }
}