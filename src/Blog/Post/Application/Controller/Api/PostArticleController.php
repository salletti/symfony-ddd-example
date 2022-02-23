<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Controller\Api;

use App\Blog\Post\Application\Event\OnPublicationRequestedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/", name="api_article_post", methods={"POST"})
 */
final class PostArticleController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $parameters = json_decode(
            $request->getContent(),
            true, 512,
            JSON_THROW_ON_ERROR
        );

        $this->eventDispatcher->dispatch(new OnPublicationRequestedEvent(
            $parameters['title'],
            $parameters['body'],
            $parameters['author'],
            $parameters['categorySlug'],
        ));

        return JsonResponse::fromJsonString(
            $request->getSession()->get('last_article_created')
        );
    }
}
