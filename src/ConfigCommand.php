<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 17-2-5
 * Time: 11:47 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{

    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('config')->setDescription('Edit docker-compose.yml');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configCommand = 'cd laradock && vim docker-compose.yml';

        passthru($configCommand);
    }
}
