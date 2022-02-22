<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\ParamConverter;

use App\Blog\Post\Application\Model\CreateArticleCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateArticleParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $createArticleCommand = new CreateArticleCommand();
        $createArticleCommand->setTitle($parameters['title']);
        $createArticleCommand->setBody($parameters['body']);
        $createArticleCommand->setAuthor($parameters['author']);

        $request->attributes->set($configuration->getName(), $createArticleCommand);
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'createArticleCommand' === $configuration->getName();
    }
}
