<?php

namespace App\Command;

use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get',
    description: 'Add a short description for your command',
)]
class GetCommand extends Command
{
    public function __construct(private LocationRepository $locationRepository)
    {
        parent::__construct();
        // $this->$locationRepository = $locationRepository;
    }

    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $locations = $this->locationRepository->findAll();
        
        $locationsTable = [];
        foreach($locations as $location)
        {
            $locationsTable [] = [$location->getName() , $location->getCountryCode() ];
        }
        $io->table(
            [ 'Name' , 'Country Code '] ,
            $locationsTable
        );
        // $io->writeln($locations);

        return Command::SUCCESS;
    }
}
