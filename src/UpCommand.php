<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:27 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class UpCommand extends Command
{
    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('up')
            ->setDescription('Starts containers')
            ->addArgument('containers', InputArgument::IS_ARRAY, 'Containers', null)
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
        $containers = $input->getArgument('containers');

        if (count($containers) === 0) {
            throw new RuntimeException('No container selected');
        }

        $upParameter = implode(' ', $containers);

        $output->writeln('<info>Starting '. $upParameter. '</info>');

        $process = new Process('cd laradock && docker-compose up -d '. $upParameter);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        $output->writeln('<comment>Done.</comment>');
    }

    /**
     * Get containers
     *
     * @return array containers
     */
    protected function defaultContainers()
    {
        return [
            'nginx',
            'redis',
            'mysql',
        ];
    }
}