<?php

namespace App\Command;

use App\Controller\AuthController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Request;

#[AsCommand(
    name: 'go',
    description: 'Add a short description for your command',
)]
class GoControllerCommand extends Command
{
    public function __construct(private AuthController $authController)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = [
            "email" => "unknown@gmail.com",
            "password" => "zxcsdadf45s"
        ];

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );


        $this->authController->login($request);

        return Command::SUCCESS;
    }
}
