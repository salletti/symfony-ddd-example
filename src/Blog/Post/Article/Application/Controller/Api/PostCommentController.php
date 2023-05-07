<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Controller\Api;

use App\Blog\Post\Article\Application\Model\CreateCommentCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments/', name: 'api_comment_post', methods: ['POST'])]
#[ParamConverter('createCommentCommand', converter: 'CreateComment')]
final class PostCommentController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateCommentCommand $createCommentCommand): JsonResponse
    {
        $comment = $this->handle($createCommentCommand);

        return JsonResponse::fromJsonString($comment);
    }
}
