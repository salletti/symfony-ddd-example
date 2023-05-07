<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Controller;

use App\Blog\Post\Category\Application\Model\CreateCategoryCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories/', name: 'api_category_post', methods: ['POST'])]
final class PostCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $parameters = json_decode(
            $request->getContent(),
            true, 512,
            JSON_THROW_ON_ERROR
        );

        $createCategoryCommand = new CreateCategoryCommand(
            $parameters['name'],
            $parameters['slug']
        );

        return JsonResponse::fromJsonString($this->handle($createCategoryCommand));
    }
}
