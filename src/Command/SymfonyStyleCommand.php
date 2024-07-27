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
    name: 'app:symfony-style',
    description: 'Learning SymfonyStyle',
)]
class SymfonyStyleCommand extends Command
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

        // $io->writeln('This is the writeln()');
        // $io->title('This is a title');
        // $io->section('This is a section');

        // $io->note('This is a note');
        // $io->warning('This is a warning');
        // $io->success('This is a success');
        // $io->error('This is an error');
        // $io->info('This is an info');
        // $io->caution('This is a caution');

        // $name = $io->ask("What is your name ?");
        // $io->writeln("Hello $name");

        // $answer = $io->confirm ("Are you sure ?");
        // $io->writeln("You selected ".($answer? "YES" : "NO"));

        // $choice = $io->choice('Select a choice' , ['A','B','C','D']);
        // $io->writeln('Your choice is : '.$choice);

        // $items = ['toyota' , 'honda' , 'mercedes'];
        // $io->listing($items);

        // $io->horizontalTable(
        //     ['title 1' , 'title 2'],
        //     [
        //         ['row1 c1' , 'row1 c2'],
        //         ['row2 c1' , 'row2 c2'],
        //     ]
        // );

        $items = ['a' ,'b' ,'c', 'd', 'e'];

        $io->progressStart(5);
        foreach($items as $item)
        {
            $io->progressAdvance();
            sleep(2);
        }
        $io->progressFinish();

        return Command::SUCCESS;
    }
}
