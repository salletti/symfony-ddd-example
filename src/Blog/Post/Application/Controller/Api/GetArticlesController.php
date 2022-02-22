<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Controller\Api;

use App\Blog\Post\Application\Model\FindArticleQuery;
use App\Blog\Post\Application\Service\ArticleFinderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/{id}", name="api_article", methods={"GET"}, options={"expose"=true})
 */
final class GetArticlesController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(string $id): JsonResponse
    {
        $article = $this->handle(new FindArticleQuery($id));

        return JsonResponse::fromJsonString($article);
    }
}
