<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 17-2-13
 * Time: 12:58 PM
 */

namespace LorinLee\LaradockCli\Console;

use LorinLee\LaradockCli\Console\RepoConfigManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RepoCommand extends Command
{
    /**
     * Configure the command option
     *
     * @return void
     */
    public function configure()
    {
        $this
            ->setName('repo')
            ->setDescription('Set LaraDock git repo, you can use your own repo.')
            ->addArgument('name', InputArgument::OPTIONAL, 'LaraDock git repo config name', RepoConfigManager::getDefaultConfigName())
            ->addArgument('repo', InputArgument::OPTIONAL, 'LaraDock git repo src', RepoConfigManager::getDefaultConfigRepo())
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
        $name = $input->getArgument('name');
        $repo = $input->getArgument('repo');
        $result = RepoConfigManager::set($name, $repo);

        if (! $result) throw new RuntimeException('Failed');

        $output->writeln('<info>Done. Set '. $name. ': '. $repo.'</info>');
    }
}