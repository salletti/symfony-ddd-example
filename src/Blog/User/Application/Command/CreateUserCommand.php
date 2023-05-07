<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Command;

use App\Blog\User\Application\Service\CreateUserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    aliases: ['app:add-user'],
    hidden: false
)]
final class CreateUserCommand extends Command
{
    private CreateUserService $createUserService;

    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a user...')
                ->addArgument('email', InputArgument::REQUIRED, 'User email')
                ->addArgument('password', InputArgument::REQUIRED, 'User password')
                ->addArgument('role', InputArgument::REQUIRED, 'User role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $output->writeln('You are about to create user');

        $user = $this->createUserService->handle(
            $input->getArgument('email'),
            [$input->getArgument('role')],
            $input->getArgument('password')
        );

        $output->writeln([
            'User Created:',
            '',
        ]);

        $output->writeln($user);

        return Command::SUCCESS;
    }
}
