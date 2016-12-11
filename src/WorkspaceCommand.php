<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:33 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class WorkspaceCommand extends Command
{
    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('workspace')
            ->setDescription('Enters the workspace')
            ->addOption('root', null, InputOption::VALUE_OPTIONAL, 'Using root', null)
        ;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $workspace_command = 'cd laradock && docker-compose exec ';
        if (! $input->getOption('root')) {
            $workspace_command .= '--user=laradock ';
        }

        $workspace_command .= 'workspace bash';

        passthru($workspace_command);

    }


}