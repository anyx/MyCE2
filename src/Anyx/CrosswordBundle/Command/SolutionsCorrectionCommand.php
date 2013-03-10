<?php

namespace Anyx\CrosswordBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SolutionsCorrectionCommand extends ContainerAwareCommand
{
    /**
     * 
     */
    protected function configure()
    {
        $this
            ->setName('site:corrections:fix')
            ->setDescription('Saving solutions correction')
        ;
    }

    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('anyx.dm');
        $solutionsRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Solution');

        foreach($solutionsRepository->findAll() as $solution) {
            $solution->saveCorrection();
        }
        
        $dm->flush();
    }
}