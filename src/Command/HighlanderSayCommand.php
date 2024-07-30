<?php

namespace App\Command;

use App\Service\Highlander;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

#[AsCommand(
    name: 'highlander:say',
    description: 'Add a short description for your command',
)]
class HighlanderSayCommand extends Command
{
    public function __construct (
        private Highlander $highlander,
        private Environment $twigEnvironment
        )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $forecasts = $this->highlander->say(-1, -5);
        $io->listing($forecasts);

        $csv = $this->twigEnvironment->render('weather/highlander_says.csv.twig', [ 'forecasts' => $forecasts , 'threshold' => 50 , 'trials' => 0]);
        $io->write($csv);
        
        return Command::SUCCESS;
    }
}
