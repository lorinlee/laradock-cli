<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:30 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class PsCommand extends Command
{

    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('ps')
            ->setDescription('Lists all the running containers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $psCommand = 'cd ' . Helpers::getLaradockDirectoryName() . ' && docker-compose ps';

        $process = new Process($psCommand);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }
}
