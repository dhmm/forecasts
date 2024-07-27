<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:verbosity',
    description: 'Add a short description for your command',
)]
class VerbosityCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->writeln('Here we print a message');

        if($io->isVerbose())
        {
            $io->writeln('This is VERBOSE');
        }

        if($io->isVeryVerbose())
        {
            $io->writeln('This is VERY VERBOSE');
        }

        if($io->isDebug())
        {
            $io->writeln('This is DEBUG');
        }

        $output->writeln('Here we print a message');

        if($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE)
        {
            $output->writeln('This is VERBOSE');
        }

        if($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE)
        {
            $output->writeln('This is VERY VERBOSE');
        }

        if($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG)
        {
            $output->writeln('This is DEBUG');
        }

        return Command::SUCCESS;
    }
}
