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
            ->addArgument('repo', InputArgument::OPTIONAL, 'Configured repo', null);
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

        if (file_exists('laradock')) {
            throw new RuntimeException('laradock exists');
        }

        $repoName = $input->getArgument('repo');
        $repo = $this->laradockRepo($repoName);

        $initCommand = file_exists('.git') ? 'git submodule add' : 'git clone';

        $process = new Process($initCommand. ' '. $repo);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $output->writeln('<info>Init Laradock, Source: '. $repo. '</info>');

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
    protected function laradockRepo($repoName)
    {
        return RepoConfigManager::get($repoName);
    }

}
