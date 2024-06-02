<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\Http\Api;

use App\Core\Domain\Command\CommandBus;
use App\Editorial\Application\Command\CreateTag\CreateTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTagController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus
    ){}

    #[Route("/api/v1/editorial/tag", name: "editorial.tag.create", methods: ["POST"])]
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->commandBus->handle(new CreateTag($data['name']));

        return new Response(content: '', status: Response::HTTP_OK);
    }
}