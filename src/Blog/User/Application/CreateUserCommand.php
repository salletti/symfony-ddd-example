<?php

declare(strict_types=1);

namespace App\Blog\User\Application;

use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
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
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

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
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');

        $user = new User(Uuid::uuid4()->toString());
        $user->setEmail($input->getArgument('email'));
        $user->setRoles([$input->getArgument('role')]);
        $user->setPassword($input->getArgument('password'));

        $this->userRepository->save($user);

        return Command::SUCCESS;
    }
}
