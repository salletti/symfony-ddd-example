<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Controller\Api;

use App\Blog\Post\Application\Model\CreateArticleCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/articles/", name="api_article_post", methods={"POST"}, options={"expose"=true})
 *
 * @ParamConverter(name="createArticleCommand", converter="CreateArticle")
 */
final class PostArticleController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request, CreateArticleCommand $createArticleCommand): JsonResponse
    {
        $article = $this->handle($createArticleCommand);

        return JsonResponse::fromJsonString($article);
    }
}
