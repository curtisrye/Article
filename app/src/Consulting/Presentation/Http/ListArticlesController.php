<?php

declare(strict_types=1);

namespace App\Consulting\Presentation\Http;

use App\Consulting\Domain\Finder\ArticleFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListArticlesController extends AbstractController
{
    public function __construct(
       private readonly ArticleFinder $articleFinder
    ) {}

    #[Route("/", name: "consulting.list.articles")]
    public function __invoke(): Response
    {
        $articles = $this->articleFinder->findAll();

        return $this->render('consulting/list-articles.html.twig', ['articles' => $articles]);
    }
}