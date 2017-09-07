<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FoobarCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:foobar')
            ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bar = new ProgressBar($output);
        $bar->setFormat('%bar% (%message%)');
        $bar->setMessage('Task starts');
        $bar->start();
        for($i=0;$i<50;$i++) {
            $bar->advance();
            usleep(30000);
        }
        $bar->setMessage('Task is finished');
        $bar->finish();
        $output->writeln('Command result.');
    }

}
