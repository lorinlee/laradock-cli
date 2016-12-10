<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:33 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;


class InitCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Creates Laradock for current Laravel project')
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
        $fs = new Filesystem();

        if ($fs->exists('laradock')) {
            throw new RuntimeException('laradock exists');
        }

        $process = new Process('git submodule add '. $this->laradockRepo());

        if ('\\' !== DIRECTORY_SEPARATOR && $fs->exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $output->writeln('<info>Init Laradock...</info>');

        $process->run(function ($type, $line) use ($output) {
            $output->writeln($line);
        });

        $output->writeln('<comment>Done.</comment>');

    }

    /**
     * Get the laradock repo
     *
     * @return string Laradock repo
     */
    protected function laradockRepo()
    {
        return 'https://github.com/LaraDock/laradock.git';
    }

}
